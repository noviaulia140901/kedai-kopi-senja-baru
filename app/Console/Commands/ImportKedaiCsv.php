<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\IngredientPrice;
use App\Models\Menu;
use App\Models\Recipe;
use App\Models\StockEntry;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

#[Signature('app:import-kedai-csv')]
#[Description('Import data CSV Kedai Kopi Senja')]
class ImportKedaiCsv extends Command
{
    public function handle(): int
    {
        try {
            DB::transaction(function () {
                $folder = database_path('data');

                // 1. Membaca lima file CSV.
                $categoriesCsv = $this->readCsv(
                    $folder . '/master_kategori_menu.csv',
                    ['kode_kategori', 'nama_kategori']
                );

                $ingredientsCsv = $this->readCsv(
                    $folder . '/bahan_final.csv',
                    [
                        'kode',
                        'nama_bahan',
                        'stok_awal',
                        'satuan_dasar',
                        'harga_per_satuan_dasar',
                        'stok_minimum',
                    ]
                );

                $menusCsv = $this->readCsv(
                    $folder . '/menu_final.csv',
                    ['kode', 'nama_menu', 'kategori', 'harga_jual']
                );

                $recipesCsv = $this->readCsv(
                    $folder . '/resep_final.csv',
                    ['kode_menu', 'nama_bahan', 'takaran', 'satuan']
                );

                $stockCsv = $this->readCsv(
                    $folder . '/seed_stok_demo.csv',
                    ['nama_bahan', 'jumlah', 'satuan']
                );

                // 2. Import kategori.
                $categoryIds = [];

                foreach ($categoriesCsv as $row) {
                    $name = trim($row['nama_kategori']);

                    $category = Category::updateOrCreate(
                        ['name' => $name],
                        ['is_active' => true]
                    );

                    $categoryIds[$name] = $category->id;
                }

                // 3. Import bahan baku dan harga.
                $ingredientIds = [];
                $initialStocks = [];

                foreach ($ingredientsCsv as $row) {
                    $name = trim($row['nama_bahan']);
                    $key = mb_strtolower($name);

                    $ingredient = Ingredient::whereRaw(
                        'LOWER(name) = ?',
                        [$key]
                    )->first();

                    // Menggunakan data percobaan CRUD sebelumnya.
                    if (! $ingredient && $key === 'biji kopi arabika') {
                        $ingredient = Ingredient::whereRaw(
                            'LOWER(name) = ?',
                            ['kopi arabika']
                        )->first();
                    }

                    if (! $ingredient) {
                        $ingredient = new Ingredient();
                    }

                    $initialStock = (float) $row['stok_awal'];

                    $ingredient->fill([
                        'name' => $name,
                        'base_unit' => trim($row['satuan_dasar']),
                        'current_stock' => $initialStock,
                        'minimum_stock' => 0,
                        'is_active' => true,
                    ]);

                    $ingredient->save();

                    $ingredientIds[$key] = $ingredient->id;
                    $initialStocks[$key] = $initialStock;

                    $price = IngredientPrice::firstOrNew([
                        'ingredient_id' => $ingredient->id,
                    ]);

                    $price->price_per_unit =
                        (float) $row['harga_per_satuan_dasar'];

                    $price->save();
                }

                // 4. Import menu.
                $menuIds = [];

                foreach ($menusCsv as $row) {
                    $code = trim($row['kode']);
                    $name = trim($row['nama_menu']);
                    $key = mb_strtolower($name);

                    $categoryName = trim($row['kategori']);

                    if (! isset($categoryIds[$categoryName])) {
                        throw new RuntimeException(
                            "Kategori tidak ditemukan: {$categoryName}"
                        );
                    }

                    $menu = Menu::whereRaw(
                        'LOWER(name) = ?',
                        [$key]
                    )->first();

                    if (! $menu) {
                        $menu = new Menu();
                    }

                    $menu->fill([
                        'name' => $name,
                        'category_id' => $categoryIds[$categoryName],
                        'selling_price' => (float) $row['harga_jual'],
                        'is_active' => true,
                    ]);

                    $menu->save();

                    $menuIds[$code] = $menu->id;
                }

                // 5. Import resep.
                $recipeCount = 0;

                foreach ($recipesCsv as $row) {
                    $menuCode = trim($row['kode_menu']);
                    $ingredientName = mb_strtolower(
                        trim($row['nama_bahan'])
                    );

                    if (! isset($menuIds[$menuCode])) {
                        throw new RuntimeException(
                            "Kode menu tidak ditemukan: {$menuCode}"
                        );
                    }

                    if (! isset($ingredientIds[$ingredientName])) {
                        throw new RuntimeException(
                            "Bahan resep tidak ditemukan: {$row['nama_bahan']}"
                        );
                    }

                    $ingredient = Ingredient::findOrFail(
                        $ingredientIds[$ingredientName]
                    );

                    $recipeUnit = trim($row['satuan']);

                    if ($recipeUnit !== $ingredient->base_unit) {
                        throw new RuntimeException(
                            "Satuan resep tidak cocok untuk {$ingredient->name}"
                        );
                    }

                    Recipe::updateOrCreate(
                        [
                            'menu_id' => $menuIds[$menuCode],
                            'ingredient_id' => $ingredientIds[$ingredientName],
                        ],
                        [
                            'quantity' => (float) $row['takaran'],
                        ]
                    );

                    $recipeCount++;
                }

                // 6. Import stok demo.
                $manager = User::where(
                    'email',
                    'manajer@kedaikopisenja.test'
                )->firstOrFail();

                $demoStockTotal = [];

                foreach ($stockCsv as $row) {
                    $name = mb_strtolower(trim($row['nama_bahan']));

                    if (! isset($ingredientIds[$name])) {
                        throw new RuntimeException(
                            "Bahan stok demo tidak ditemukan: {$row['nama_bahan']}"
                        );
                    }

                    $ingredientId = $ingredientIds[$name];

                    $ingredient = Ingredient::findOrFail($ingredientId);

                    $quantity = (float) $row['jumlah'];
                    $unit = strtolower(trim($row['satuan']));

                    // Konversi liter menjadi ml.
                    if (
                        $unit === 'liter'
                        && $ingredient->base_unit === 'ml'
                    ) {
                        $quantity *= 1000;
                    } elseif ($unit !== $ingredient->base_unit) {
                        throw new RuntimeException(
                            "Satuan stok demo tidak cocok untuk {$ingredient->name}"
                        );
                    }

                    $notes = 'Stok demo dari seed_stok_demo.csv';

                    $entry = StockEntry::firstOrNew([
                        'ingredient_id' => $ingredientId,
                        'notes' => $notes,
                    ]);

                    $entry->fill([
                        'entered_by' => $manager->id,
                        'quantity' => $quantity,
                        'unit_price' => 0,
                        'total_price' => 0,
                        'supplier_name' => null,
                        'entry_date' => now()->toDateString(),
                    ]);

                    $entry->save();

                    $demoStockTotal[$name] = $quantity;
                }

                // 7. Mengatur stok akhir tanpa menggandakan stok
                // ketika command dijalankan ulang.
                foreach ($initialStocks as $name => $initialStock) {
                    $demoQuantity = $demoStockTotal[$name] ?? 0;

                    Ingredient::whereKey($ingredientIds[$name])
                        ->update([
                            'current_stock' => $initialStock + $demoQuantity,
                        ]);
                }

                // 8. Menampilkan jumlah data.
                $this->line('Kategori: ' . count($categoriesCsv));
                $this->line('Bahan baku: ' . count($ingredientsCsv));
                $this->line('Menu: ' . count($menusCsv));
                $this->line('Resep: ' . $recipeCount);
                $this->line('Stok demo: ' . count($stockCsv));
            });

            $this->info('Import CSV berhasil.');

            return self::SUCCESS;

        } catch (Throwable $e) {
            $this->error(
                'Import gagal: ' . $e->getMessage()
                . ' | Lokasi: ' . $e->getFile()
                . ':' . $e->getLine()
            );

            return self::FAILURE;
        }
    }

    private function readCsv(string $path, array $requiredHeaders): array
    {
        if (! file_exists($path)) {
            throw new RuntimeException(
                "File CSV tidak ditemukan: {$path}"
            );
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException(
                "File CSV tidak bisa dibuka: {$path}"
            );
        }

        try {
            // Membaca header tanpa escape character khusus.
            $headers = fgetcsv($handle, 0, ',', '"', '');

            if ($headers === false) {
                throw new RuntimeException(
                    "Header CSV kosong: {$path}"
                );
            }

            // Membersihkan BOM dan spasi pada setiap header.
            $headers = array_map(function ($header) {
                return trim(
                    preg_replace('/^\xEF\xBB\xBF/', '', $header)
                );
            }, $headers);

            // Memastikan semua kolom wajib tersedia.
            foreach ($requiredHeaders as $requiredHeader) {
                if (! in_array($requiredHeader, $headers, true)) {
                    throw new RuntimeException(
                        "Kolom {$requiredHeader} tidak ditemukan di {$path}"
                    );
                }
            }

            $rows = [];
            $line = 1;

            while (($values = fgetcsv($handle, 0, ',', '"', '')) !== false) {
                $line++;

                if ($values === [null]) {
                    continue;
                }

                if (count($values) !== count($headers)) {
                    throw new RuntimeException(
                        "Jumlah kolom tidak sesuai di {$path}, baris {$line}"
                    );
                }

                $rows[] = array_combine($headers, $values);
            }

            return $rows;

        } finally {
            fclose($handle);
        }
    }
}
