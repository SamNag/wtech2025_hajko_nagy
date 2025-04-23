<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Package;
use App\Models\Tag;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productImages = [
            'a', 'argan', 'b1', 'b2', 'b3', 'b5', 'b6', 'b7', 'b9', 'b12', 'b-complex', 'c', 'calcium', 'castor', 'cbd',
            'chromium', 'cod-liver', 'copper', 'd3', 'e', 'flaxseed', 'grape', 'hemp', 'iodine', 'iron', 'jojoba', 'k2',
            'krill', 'magnesium', 'manganese', 'mct', 'mg', 'mg-camgzn', 'mg-pt', 'multi', 'olive', 'omega3', 'phosporus',
            'potassium', 'primrose', 'rosehip', 'selen-e', 'selenium', 'teatree', 'zinc', 'zinc-c'
        ];


        $titles = ["Vitamin A Softgels", "Argan Oil Elixir", "Vitamin B1 Capsules", "Vitamin B2 Complex", "Niacin (B3) Tablets", "Pantothenic Acid (B5)", "Pyridoxine (B6) Formula", "Biotin (B7) Beauty Support", "Folic Acid (B9)", "Vitamin B12 Energy Boost", "Vitamin B-Complex Daily", "Vitamin C Antioxidant Formula", "Calcium Bone Support", "Castor Oil Cold Pressed", "CBD Oil Natural Extract", "Chromium Picolinate", "Cod Liver Oil Capsules", "Copper Mineral Support", "Vitamin D3 Immune Booster", "Vitamin E Antioxidant Softgels", "Flaxseed Oil 1000mg", "Grape Seed Extract", "Hemp Oil Supplement", "Iodine Thyroid Support", "Iron Ferrous Tablets", "Jojoba Oil Moisturizer", "Vitamin K2 Bone Health", "Krill Oil Omega-3", "Magnesium Calm Formula", "Manganese Trace Mineral", "MCT Oil Brain Fuel", "Magnesium Capsules", "Magnesium Calcium Zinc Blend", "Magnesium Potassium Tablets", "Multivitamin Daily Formula", "Olive Oil Softgels", "Omega-3 Fish Oil", "Phosphorus Mineral Tablets", "Potassium Electrolyte Tabs", "Evening Primrose Oil", "Rosehip Oil Skin Therapy", "Selenium with Vitamin E", "Selenium Antioxidant", "Tea Tree Oil Pure", "Zinc Immune Support", "Zinc with Vitamin C Boost"];

        $descriptions = ["Supports vision and immune health, essential for night vision and cell growth.", "Nourishes skin and hair with vitamin E, ideal for daily beauty routines.", "Helps convert food into energy, supporting heart and nerve function.", "Boosts metabolism and red blood cell production for daily wellness.", "Maintains healthy skin and supports energy metabolism.", "Supports adrenal function and healthy digestion.", "Essential for protein metabolism and brain development.", "Promotes hair and nail health and supports metabolism.", "Important during pregnancy and for cell division.", "Helps with energy levels, brain function, and cell health.", "A full spectrum of B vitamins to support energy and stress response.", "Powerful antioxidant that supports immune and skin health.", "Strengthens bones and teeth, essential for muscle function.", "Natural oil known to improve scalp and skin hydration.", "Hemp-derived oil for calming and daily balance.", "Supports healthy blood sugar levels and metabolism.", "Rich source of vitamins A and D for immune and bone health.", "Trace mineral important for cardiovascular health.", "Essential for calcium absorption and immune support.", "Protects cells from oxidative damage, supports skin health.", "Plant-based omega-3 source, supports heart and brain health.", "Antioxidant-rich extract supports circulation and skin.", "Rich in essential fatty acids for general well-being.", "Supports metabolism and thyroid function with essential iodine.", "Improves oxygen transport in the blood for energy levels.", "Ideal for skin and hair care, naturally moisturizing.", "Supports calcium metabolism and cardiovascular health.", "A sustainable source of omega-3 fatty acids.", "Helps relax muscles and calm the nervous system.", "Essential for bone development and enzyme activation.", "Supports energy, metabolism, and mental focus.", "Magnesium in capsule form for muscle and nerve support.", "Blend of essential minerals for overall wellness.", "Combines magnesium and potassium for hydration and heart health.", "Daily multivitamin with essential nutrients for overall health.", "Softgel form of olive oil, good for heart health.", "Supports joint, brain, and heart health with EPA and DHA.", "Strengthens bones and balances pH in the body.", "Replenishes electrolytes, essential for muscle function.", "Hormonal support and skin health for women.", "Soothing oil known for skin regeneration and hydration.", "Combines selenium and vitamin E for antioxidant protection.", "Protects cells from damage and supports thyroid function.", "Antimicrobial properties for skin and scalp use.", "Strengthens immune function and helps wound healing.", "Immune support blend with added vitamin C."];

        $tags = [
            ['vision', 'immunity'],
            ['skin', 'hair'],
            ['energy', 'metabolism'],
            ['energy', 'digestion'],
            ['growth', 'metabolism'],
            ['blood', 'cells'],
            ['skin', 'nerves'],
            ['immune', 'nerves'],
            ['brain', 'heart'],
            ['blood', 'cells'],
            ['energy', 'nerves'],
            ['immune', 'skin'],
            ['bones', 'teeth'],
            ['hair', 'moisture'],
            ['relaxation', 'stress'],
            ['sugar', 'metabolism'],
            ['omega3', 'brain'],
            ['blood', 'cells'],
            ['bones', 'immunity'],
            ['antioxidant', 'skin'],
            ['omega3', 'heart'],
            ['heart', 'immunity'],
            ['skin', 'nails'],
            ['thyroid', 'hormones'],
            ['oxygen', 'blood'],
            ['skin', 'hair'],
            ['bones', 'heart'],
            ['omega3', 'heart'],
            ['muscle', 'nerves'],
            ['bones', 'metabolism'],
            ['energy', 'brain'],
            ['muscle', 'bones'],
            ['bones', 'immunity'],
            ['nerves', 'muscles'],
            ['overall', 'health'],
            ['heart', 'fats'],
            ['heart', 'brain'],
            ['bones', 'teeth'],
            ['fluid', 'nerves'],
            ['women', 'balance'],
            ['vitaminC', 'skin'],
            ['antioxidant', 'heart'],
            ['thyroid', 'immunity'],
            ['antiseptic', 'skin'],
            ['immune', 'skin'],
            ['immune', 'healing'],
            ['immune', 'skin']
        ];

        $categories = [
            'vitamins', 'oils', 'vitamins', 'vitamins', 'vitamins', 'vitamins', 'vitamins', 'vitamins', 'vitamins', 'vitamins',
            'vitamins', 'vitamins', 'minerals', 'oils', 'vitamins', 'minerals', 'oils', 'minerals', 'vitamins', 'vitamins',
            'oils', 'vitamins', 'oils', 'minerals', 'minerals', 'oils', 'minerals', 'oils', 'minerals', 'minerals', 'oils',
            'minerals', 'minerals', 'minerals', 'minerals', 'oils', 'oils', 'minerals', 'minerals', 'oils', 'vitamins',
            'minerals', 'oils', 'minerals', 'minerals', 'minerals'
        ];

        foreach ($productImages as $i => $name) {
            $product = Product::create([
                'id' => Str::uuid(),
                'name' => $titles[$i],
                'description' => $descriptions[$i],
                'category' => $categories[$i],
                'image1' => "assets/product_images/image/{$name}.png",
                'image2' => ($name !== 'mg') ? "assets/product_images/package/package_{$name}.png" : null,
            ]);

            // Example packages, adjust as needed
            if ($categories[$i] === 'oils') {
                $packageSizes = ['10ml', '20ml', '50ml'];
            } else {
                $packageSizes = ['30pcs', '60pcs', '120pcs'];
            }

            foreach ($packageSizes as $size) {
                Package::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'size' => $size,
                    'price' => rand(10, 100),
                    'stock' => rand(1, 20),
                ]);
            }

            // Attach tags (assuming a one-to-many relationship)
            foreach ($tags[$i] as $tag) {
                Tag::create([
                    'id' => Str::uuid(),
                    'product_id' => $product->id,
                    'tag_name' => $tag,
                ]);
            }
        }
    }
}
