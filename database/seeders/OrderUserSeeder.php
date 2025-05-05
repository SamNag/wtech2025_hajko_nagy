<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OrderUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        OrderItem::truncate();
        Order::truncate();
        Address::truncate();
        User::where('id', '!=', null)->delete(); // Keep any existing users if needed

        // Create admin user
        $adminUser = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@flakes.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'is_admin' => true,
        ]);

        // Create regular user
        $regularUser = User::create([
            'id' => Str::uuid(),
            'name' => 'Regular',
            'surname' => 'User',
            'email' => 'user@flakes.com',
            'password' => Hash::make('password'),
            'phone' => '+9876543210',
            'is_admin' => false,
        ]);

        // Create addresses for users
        $address1 = Address::create([
            'id' => Str::uuid(),
            'street' => '123 Main St',
            'city' => 'New York',
            'zip' => '10001',
            'country' => 'USA',
        ]);

        $address2 = Address::create([
            'id' => Str::uuid(),
            'street' => '456 Oak Ave',
            'city' => 'Los Angeles',
            'zip' => '90001',
            'country' => 'USA',
        ]);

        // Get all packages for random selection
        $packages = Package::all();
        if ($packages->isEmpty()) {
            $this->command->info('No packages found. Make sure to run the ProductSeeder first.');
            return;
        }

        // Create orders with dates from the last 7 days including today
        $statuses = ['created', 'shipped', 'delivered', 'canceled', 'processing'];
        $paymentMethods = ['card', 'cash'];
        $deliveryTypes = ['ups', 'fedex', 'dhl'];

        // Create orders, distributed over the past 7 days including today
        for ($dayOffset = 0; $dayOffset <= 7; $dayOffset++) {
            // Calculate the date
            $orderDate = Carbon::now()->subDays($dayOffset);

            // Create more orders for recent days (5-10 orders per day for recent days, 2-4 for older days)
            $numOrders = $dayOffset <= 3 ? rand(5, 10) : rand(2, 4);

            for ($i = 0; $i < $numOrders; $i++) {
                // Alternate between users, slightly more orders for regular user
                $user = ($i % 3 == 0) ? $adminUser : $regularUser;
                $address = ($i % 2 == 0) ? $address1 : $address2;

                // Create order
                $order = Order::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'address_id' => $address->id,
                    'payment' => $paymentMethods[array_rand($paymentMethods)],
                    'status' => $statuses[array_rand($statuses)],
                    'price' => 0, // Will calculate based on items
                    'delivery_type' => $deliveryTypes[array_rand($deliveryTypes)],
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // Add 1-4 items to each order
                $numItems = rand(1, 4);
                $orderTotal = 0;

                for ($j = 0; $j < $numItems; $j++) {
                    // Pick a random package
                    $package = $packages->random();
                    $quantity = rand(1, 3);

                    // Create order item
                    OrderItem::create([
                        'id' => Str::uuid(),
                        'order_id' => $order->id,
                        'package_id' => $package->id,
                        'quantity' => $quantity,
                    ]);

                    $orderTotal += $package->price * $quantity;
                }

                // Add shipping cost
                $shippingCost = 3.65;
                $orderTotal += $shippingCost;

                // Update the order with the calculated total
                $order->update([
                    'price' => $orderTotal
                ]);
            }
        }

        // Summary
        $this->command->info('Created admin user: admin@flakes.com with password: password');
        $this->command->info('Created regular user: user@flakes.com with password: password');
        $this->command->info('Created ' . Order::count() . ' orders with ' . OrderItem::count() . ' order items');
    }
}
