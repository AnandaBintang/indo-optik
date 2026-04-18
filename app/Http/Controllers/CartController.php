<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\PromoService;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService   $cartService,
        protected PromoService  $promoService,
        protected SettingService $settingService,
    ) {}

    // -------------------------------------------------------------------------
    // Cart display
    // -------------------------------------------------------------------------

    /**
     * Show the shopping cart page.
     */
    public function index(): View
    {
        $items    = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();

        return view('pages.cart.index', compact('items', 'subtotal'));
    }

    // -------------------------------------------------------------------------
    // Mutations
    // -------------------------------------------------------------------------

    /**
     * Add a product to the cart.
     */
    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id'        => 'required|exists:products,id',
            'color'             => 'nullable|string|max:50',
            'lens_type'         => 'nullable|string|max:100',
            'lens_price'        => 'nullable|integer|min:0',
            'quantity'          => 'integer|min:1|max:10',
            'delivery_type'     => 'nullable|in:pickup,delivery',
            'prescription_data' => 'nullable|array',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $this->cartService->add([
            'product_id'        => $product->id,
            'product_name'      => $product->name,
            'product_price'     => $product->effective_price,
            'lens_type'         => $validated['lens_type']         ?? 'Standar',
            'lens_price'        => $validated['lens_price']        ?? 0,
            'color'             => $validated['color']             ?? 'Hitam',
            'quantity'          => $validated['quantity']          ?? 1,
            'delivery_type'     => $validated['delivery_type']     ?? 'pickup',
            'image'             => $product->image,
            'prescription_data' => $validated['prescription_data'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'count'   => $this->cartService->getCount(),
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Remove a specific item from the cart by its unique key.
     */
    public function remove(string $key): RedirectResponse
    {
        $this->cartService->remove($key);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Update the quantity of a specific cart item.
     */
    public function update(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $this->cartService->update($key, (int) $validated['quantity']);

        if ($request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'count'    => $this->cartService->getCount(),
                'subtotal' => $this->cartService->getSubtotal(),
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    // -------------------------------------------------------------------------
    // Checkout
    // -------------------------------------------------------------------------

    /**
     * Process checkout — build a WhatsApp message and redirect to wa.me.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'promo_code'    => 'nullable|string|max:50',
        ]);

        if ($this->cartService->getCount() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda masih kosong.');
        }

        $discount  = 0;
        $promoCode = null;

        // Apply promo code if provided, and increment usage
        if (! empty($validated['promo_code'])) {
            $subtotal = $this->cartService->getSubtotal();
            $result   = $this->promoService->apply($validated['promo_code'], $subtotal);

            if ($result['valid']) {
                $discount  = (int) $result['discount'];
                $promoCode = $validated['promo_code'];
            }
        }

        $waNumber = $this->settingService->get('whatsapp_number', '6281234567890');

        $waUrl = $this->cartService->buildWhatsAppMessage(
            customerName: $validated['customer_name'],
            promoCode:    $promoCode,
            discount:     $discount,
            waNumber:     $waNumber,
        );

        // Clear the cart after building the WA message
        $this->cartService->clear();

        return redirect()->away($waUrl);
    }

    // -------------------------------------------------------------------------
    // Promo code
    // -------------------------------------------------------------------------

    /**
     * Validate and return the discount for a promo code (AJAX).
     */
    public function applyPromo(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $subtotal = $this->cartService->getSubtotal();
        $result   = $this->promoService->validate($validated['code'], $subtotal);

        if (! $result['valid']) {
            return response()->json([
                'valid'   => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'valid'    => true,
            'message'  => $result['message'],
            'discount' => (int) $result['discount'],
            'total'    => max(0, $subtotal - (int) $result['discount']),
            'promo'    => [
                'code'  => $result['promo']->code,
                'label' => $result['promo']->label ?? $result['promo']->code,
                'type'  => $result['promo']->type,
                'value' => $result['promo']->value,
            ],
        ]);
    }
}
