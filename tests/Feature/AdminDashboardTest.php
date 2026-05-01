<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('admin dashboard is catalog focused for whatsapp only checkout', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_ADMIN,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertSee('WhatsApp-only storefront')
        ->assertSee('Produk Aktif')
        ->assertDontSee('Total Pendapatan')
        ->assertDontSee('Pesanan Terbaru');
});

test('admin order dashboard routes are not registered', function () {
    expect(Route::has('admin.orders.index'))->toBeFalse()
        ->and(Route::has('admin.orders.show'))->toBeFalse()
        ->and(Route::has('admin.orders.update-status'))->toBeFalse();
});
