<?php

namespace App\Services;

use App\Models\IngredientPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class SupplierPriceService
{
    public function sync(): array
    {
        $baseUrl = rtrim(
            (string) config('services.supplier.base_url'),
            '/'
        );

        $apiKey = config('services.supplier.api_key');

        if ($baseUrl === '' || ! is_string($apiKey) || trim($apiKey) === '') {
            return [
                'success' => false,
                'message' => 'Konfigurasi API Supplier belum lengkap. Harga terakhir tetap digunakan.',
                'updated' => 0,
            ];
        }

        try {
            $response = Http::acceptJson()
                ->withHeaders([
                    'X-API-Key' => $apiKey,
                ])
                ->timeout(15)
                ->get($baseUrl.'/harga-bahan');

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Gagal mengambil harga dari supplier. Harga terakhir tetap digunakan.',
                    'updated' => 0,
                ];
            }

            $payload = $response->json();

            if (
                ! is_array($payload) ||
                ! isset($payload['diperbarui'], $payload['data']) ||
                ! is_string($payload['diperbarui']) ||
                ! is_array($payload['data'])
            ) {
                return [
                    'success' => false,
                    'message' => 'Format data supplier tidak valid. Harga terakhir tetap digunakan.',
                    'updated' => 0,
                ];
            }

            try {
                $supplierDate = Carbon::parse($payload['diperbarui']);
            } catch (Throwable) {
                return [
                    'success' => false,
                    'message' => 'Tanggal pembaruan supplier tidak valid. Harga terakhir tetap digunakan.',
                    'updated' => 0,
                ];
            }

            $updated = DB::transaction(function () use ($payload, $supplierDate) {
                $total = 0;

                foreach ($payload['data'] as $item) {
                    if (
                        ! is_array($item) ||
                        ! isset(
                            $item['kode'],
                            $item['satuan_dasar'],
                            $item['harga_per_satuan']
                        ) ||
                        ! is_string($item['kode']) ||
                        ! is_string($item['satuan_dasar']) ||
                        ! is_numeric($item['harga_per_satuan']) ||
                        (float) $item['harga_per_satuan'] < 0
                    ) {
                        continue;
                    }

                    $price = IngredientPrice::with('ingredient')
                        ->where('supplier_code', $item['kode'])
                        ->first();

                    if (! $price || ! $price->ingredient) {
                        continue;
                    }

                    if ($price->ingredient->base_unit !== $item['satuan_dasar']) {
                        continue;
                    }

                    $price->update([
                        'price_per_unit' => $item['harga_per_satuan'],
                        'supplier_updated_at' => $supplierDate,
                        'fetched_at' => now(),
                    ]);

                    $total++;
                }

                return $total;
            });

            return [
                'success' => true,
                'message' => $updated > 0
                    ? "Berhasil memperbarui {$updated} harga bahan dari supplier."
                    : 'API berhasil dihubungi, tetapi tidak ada kode bahan yang cocok untuk diperbarui.',
                'updated' => $updated,
            ];
        } catch (Throwable) {
            return [
                'success' => false,
                'message' => 'Terjadi kendala saat menghubungi supplier. Harga terakhir tetap digunakan.',
                'updated' => 0,
            ];
        }
    }
}
