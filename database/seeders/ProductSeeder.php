<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Package;
use App\Models\Tag;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        Tag::truncate();
        Package::truncate();
        Product::truncate();

        // Load Excel file
        $spreadsheet = IOFactory::load(database_path('seeders/productlist.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $name = $row['A'];
            $category = $row['B'];
            $basePrice = (float)$row['C'];
            $tags = explode(',', $row['D']);
            $packageOptions = explode(',', $row['E']);
            $packageType = trim($row['F']);
            $img = $row['G'];
            $imgPackage = $row['H'] !== 'null' ? $row['H'] : null;
            $description = $row['I'];

            $product = Product::create([
                'id' => Str::uuid(),
                'name' => $name,
                'category' => $category,
                'description' => $description,
                'image1' => "assets/product_images/image/{$img}.png",
                'image2' => $imgPackage ? "assets/product_images/package/{$imgPackage}.png" : null,
            ]);

            foreach ($packageOptions as $index => $option) {
                $size = trim($option) . $packageType;
                $price = match ($index) {
                    0 => $basePrice,
                    1 => round($basePrice * 1.2, 2),
                    2 => round($basePrice * 1.3, 2),
                    default => $basePrice,
                };

                Package::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => $price,
                    'stock' => rand(5, 25),
                ]);
            }

            foreach ($tags as $tag) {
                Tag::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'tag_name' => trim($tag),
                ]);
            }
        }
    }
}
