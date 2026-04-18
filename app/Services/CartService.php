<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartService
{
    const SESSION_KEY = 'cart';
    const COUNT_KEY   = 'cart_count';

    // -------------------------------------------------------------------------
    // Mutators
    // -------------------------------------------------------------------------

    /**
     * Add an item to the session cart.
     *
     * Expected item keys:
     *   product_id, product_name, product_price, lens_type, lens_price,
     *   color, quantity, delivery_type, image, prescription_data
     */
    public function add(array $item): void
    {
        $cart = $this->getItems();

        // Generate a unique key for this cart entry so the same product can
        // appear multiple times with different configurations.
        $key = $this->generateKey($item);

        if (isset($cart[$key])) {
            // If an identical configuration already exists, just bump the qty.
            $cart[$key]['quantity'] = (int) $cart[$key]['quantity'] + (int) ($item['quantity'] ?? 1);
        } else {
            $cart[$key] = [
                'key'               => $key,
                'product_id'        => $item['product_id'],
                'product_name'      => $item['product_name'],
                'product_price'     => (int) $item['product_price'],
                'lens_type'         => $item['lens_type']         ?? 'Standar',
                'lens_price'        => (int) ($item['lens_price'] ?? 0),
                'color'             => $item['color']             ?? 'Hitam',
                'quantity'          => (int) ($item['quantity']   ?? 1),
                'delivery_type'     => $item['delivery_type']     ?? 'pickup',
                'image'             => $item['image']             ?? null,
                'prescription_data' => $item['prescription_data'] ?? null,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        $this->syncCount($cart);
    }

    /**
     * Remove an item from the cart by its unique key.
     */
    public function remove(string $key): void
    {
        $cart = $this->getItems();

        unset($cart[$key]);

        Session::put(self::SESSION_KEY, $cart);
        $this->syncCount($cart);
    }

    /**
     * Update the quantity of a specific cart item.
     */
    public function update(string $key, int $qty): void
    {
        $cart = $this->getItems();

        if (isset($cart[$key])) {
            if ($qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $qty;
            }
        }

        Session::put(self::SESSION_KEY, $cart);
        $this->syncCount($cart);
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        Session::forget(self::COUNT_KEY);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Return all cart items as an associative array keyed by the item key.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    /**
     * Return the total number of individual units across all cart items.
     */
    public function getCount(): int
    {
        return (int) Session::get(self::COUNT_KEY, 0);
    }

    /**
     * Return the subtotal (sum of (product_price + lens_price) * quantity).
     */
    public function getSubtotal(): int
    {
        $subtotal = 0;

        foreach ($this->getItems() as $item) {
            $subtotal += ((int) $item['product_price'] + (int) $item['lens_price']) * (int) $item['quantity'];
        }

        return $subtotal;
    }

    // -------------------------------------------------------------------------
    // WhatsApp integration
    // -------------------------------------------------------------------------

    /**
     * Build a formatted WhatsApp message from the current cart contents and
     * return a redirect-ready wa.me URL.
     */
    public function buildWhatsAppMessage(
        string $customerName,
        ?string $promoCode = null,
        int $discount = 0,
        string $waNumber = '6281234567890',
    ): string {
        $items    = $this->getItems();
        $subtotal = $this->getSubtotal();
        $total    = max(0, $subtotal - $discount);

        $lines   = [];
        $lines[] = "Halo IndoOptik! Saya ingin memesan:";
        $lines[] = "";
        $lines[] = "Nama: {$customerName}";
        $lines[] = "";
        $lines[] = "=== PESANAN ===";

        $no = 1;
        foreach ($items as $item) {
            $itemTotal = ((int) $item['product_price'] + (int) $item['lens_price']) * (int) $item['quantity'];

            $lines[] = "";
            $lines[] = "{$no}. {$item['product_name']}";
            $lines[] = "   Warna      : {$item['color']}";
            $lines[] = "   Lensa      : {$item['lens_type']}";
            $lines[] = "   Harga Frame: Rp " . number_format($item['product_price'], 0, ',', '.');
            $lines[] = "   Harga Lensa: Rp " . number_format($item['lens_price'],   0, ',', '.');
            $lines[] = "   Qty        : {$item['quantity']}";
            $lines[] = "   Pengiriman : " . ($item['delivery_type'] === 'delivery' ? 'Dikirim' : 'Ambil di toko');
            $lines[] = "   Subtotal   : Rp " . number_format($itemTotal, 0, ',', '.');

            // Include prescription data if present
            if (! empty($item['prescription_data'])) {
                $lines[] = "   Resep      :";
                foreach ($item['prescription_data'] as $pKey => $pVal) {
                    if ($pVal !== null && $pVal !== '') {
                        $lines[] = "     - {$pKey}: {$pVal}";
                    }
                }
            }

            $no++;
        }

        $lines[] = "";
        $lines[] = "=== TOTAL ===";
        $lines[] = "Subtotal : Rp " . number_format($subtotal, 0, ',', '.');

        if ($promoCode && $discount > 0) {
            $lines[] = "Promo    : {$promoCode} (-Rp " . number_format($discount, 0, ',', '.') . ")";
        }

        $lines[] = "TOTAL    : Rp " . number_format($total, 0, ',', '.');
        $lines[] = "";
        $lines[] = "Mohon konfirmasi pesanan saya. Terima kasih!";

        $message = implode("\n", $lines);

        return "https://wa.me/{$waNumber}?text=" . rawurlencode($message);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Generate a unique but deterministic key for a cart item based on its
     * identifying attributes (product, colour, lens, delivery type).
     */
    private function generateKey(array $item): string
    {
        $raw = implode('_', [
            $item['product_id']    ?? '',
            $item['color']         ?? '',
            $item['lens_type']     ?? '',
            $item['delivery_type'] ?? '',
        ]);

        return md5($raw) . '_' . Str::random(4);
    }

    /**
     * Re-calculate and store the cart_count session value.
     */
    private function syncCount(array $cart): void
    {
        $count = 0;

        foreach ($cart as $item) {
            $count += (int) $item['quantity'];
        }

        Session::put(self::COUNT_KEY, $count);
    }
}
