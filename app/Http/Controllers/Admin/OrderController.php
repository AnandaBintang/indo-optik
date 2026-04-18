<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a paginated listing of orders with optional filters.
     */
    public function index(Request $request): View
    {
        $search       = $request->get('search');
        $status       = $request->get('status');
        $deliveryType = $request->get('delivery_type');
        $dateFrom     = $request->get('date_from');
        $dateTo       = $request->get('date_to');

        $orders = Order::with(['user', 'items', 'promoCode'])
            ->when(
                $search,
                fn ($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('customer_name', 'like', "%{$search}%")
                       ->orWhere('customer_phone', 'like', "%{$search}%")
                       ->orWhere('id', $search);
                })
            )
            ->when(
                $status !== null && $status !== '',
                fn ($q) => $q->where('status', $status)
            )
            ->when(
                $deliveryType !== null && $deliveryType !== '',
                fn ($q) => $q->where('delivery_type', $deliveryType)
            )
            ->when(
                $dateFrom,
                fn ($q) => $q->whereDate('created_at', '>=', $dateFrom)
            )
            ->when(
                $dateTo,
                fn ($q) => $q->whereDate('created_at', '<=', $dateTo)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Summary counts for the status filter badges
        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'completed'  => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled'  => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'status',
            'deliveryType',
            'dateFrom',
            'dateTo',
            'statusCounts',
        ));
    }

    /**
     * Display the specified order with all its items.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'promoCode']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Order::statuses()),
            'notes'  => 'nullable|string|max:1000',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // Guard: do not allow changing a cancelled order back to any active state
        // unless the user is an admin (staff cannot un-cancel).
        if ($oldStatus === Order::STATUS_CANCELLED && $newStatus !== Order::STATUS_CANCELLED) {
            if (! $request->user()->isAdmin()) {
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', 'Pesanan yang dibatalkan tidak dapat diubah kembali.');
            }
        }

        $updateData = ['status' => $newStatus];

        if (! empty($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $order->update($updateData);

        $oldLabel = $order->statusLabel();
        // Temporarily set the new status on the model to get the new label
        $order->status = $newStatus;
        $newLabel = $order->statusLabel();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Status pesanan #" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " berhasil diubah dari \"{$oldLabel}\" menjadi \"{$newLabel}\".");
    }
}
