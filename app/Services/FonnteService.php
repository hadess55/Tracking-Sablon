<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Kirim WhatsApp via Fonnte
     *
     * @param  string $toNomor  (format 62..., tanpa +)
     * @param  string $message  (isi pesan teks)
     * @return array|null       (respon dari Fonnte atau null kalau gagal)
     */
    public static function sendMessage(string $toNomor, string $message)
    {
        $token = config('services.fonnte.token');

        if (!$token) {
            Log::warning('Fonnte token tidak ditemukan.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $toNomor,
                'message' => $message,
                // kamu bisa tambah 'schedule', 'typing', 'countryCode', dll sesuai dokumentasi Fonnte
            ]);

            if ($response->failed()) {
                Log::error('Fonnte WA gagal', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
            }

            return $response->json();

        } catch (\Throwable $e) {
            Log::error('Fonnte WA exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
