<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Display the services page.
     */
    public function index(): View
    {
        return view('pages.services.index');
    }

    /**
     * Validate a booking request and return a WhatsApp confirmation URL.
     */
    public function storeBooking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service' => ['required', 'in:exam,consultation'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
        ], [
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh sebelum hari ini.',
            'booking_date.required' => 'Tanggal booking wajib dipilih.',
            'booking_time.required' => 'Waktu booking wajib dipilih.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon/WA wajib diisi.',
        ]);

        $validated = $validator->validate();

        $serviceLabels = [
            'exam' => 'Periksa Mata',
            'consultation' => 'Konsultasi Frame',
        ];

        $waNumber = data_get(view()->shared('settings', []), 'whatsapp_number', '6281234567890');
        $message = implode("\n", [
            'Halo IndoOptik! Saya ingin membuat janji:',
            '',
            'Nama: ' . $validated['name'],
            'Telepon: ' . $validated['phone'],
            'Layanan: ' . $serviceLabels[$validated['service']],
            'Tanggal: ' . $validated['booking_date'],
            'Waktu: ' . $validated['booking_time'],
            '',
            'Mohon konfirmasinya. Terima kasih!',
        ]);

        return response()->json([
            'success' => true,
            'wa_url' => "https://wa.me/{$waNumber}?text=" . rawurlencode($message),
        ]);
    }
}
