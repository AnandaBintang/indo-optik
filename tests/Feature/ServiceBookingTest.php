<?php

use Illuminate\Support\Facades\Date;

test('booking rejects dates before today', function () {
    Date::setTestNow('2026-06-28 10:00:00');

    $response = $this->postJson(route('services.booking.store'), [
        'service' => 'exam',
        'booking_date' => '2026-06-27',
        'booking_time' => '10:00',
        'name' => 'Bintang',
        'phone' => '081234567890',
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['booking_date'])
        ->assertJsonPath(
            'errors.booking_date.0',
            'Tanggal booking tidak boleh sebelum hari ini.'
        );
});

test('booking accepts future month dates', function () {
    Date::setTestNow('2026-06-28 10:00:00');

    $response = $this->postJson(route('services.booking.store'), [
        'service' => 'consultation',
        'booking_date' => '2026-07-03',
        'booking_time' => '14:00',
        'name' => 'Bintang',
        'phone' => '081234567890',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['wa_url']);
});
