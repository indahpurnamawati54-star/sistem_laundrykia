<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Kota Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create kasir users
        $kasir1 = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@laundry.com',
            'phone' => '081234567891',
            'address' => 'Jl. Kasir No. 1, Kota Kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        $kasir2 = User::create([
            'name' => 'Kasir 2',
            'email' => 'kasir2@laundry.com',
            'phone' => '081234567892',
            'address' => 'Jl. Kasir No. 2, Kota Kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        // Create sample customers
        $pelanggan1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567893',
            'address' => 'Jl. Pelanggan No. 1, Kota Pelanggan',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'email_verified_at' => now(),
        ]);

        $pelanggan2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '081234567894',
            'address' => 'Jl. Pelanggan No. 2, Kota Pelanggan',
            'password' => Hash::make('password'),
            'role' => 'pelanggan',
            'email_verified_at' => now(),
        ]);

        // Create services
        $services = [
            [
                'name' => 'Laundry Kiloan Reguler',
                'type' => Service::TYPE_KILOAN,
                'price_per_kg' => 7000,
                'estimated_hours' => 24,
                'discount' => 0,
                'description' => 'Laundry kiloan dengan proses reguler, selesai dalam 24 jam',
            ],
            [
                'name' => 'Laundry Kiloan Express',
                'type' => Service::TYPE_KILOAN,
                'price_per_kg' => 10000,
                'estimated_hours' => 6,
                'discount' => 0,
                'description' => 'Laundry kiloan dengan proses cepat, selesai dalam 6 jam',
            ],
            [
                'name' => 'Laundry Satuan (Baju)',
                'type' => Service::TYPE_SATUAN,
                'price_per_item' => 5000,
                'estimated_hours' => 24,
                'discount' => 10,
                'description' => 'Laundry per item baju, diskon 10%',
            ],
            [
                'name' => 'Laundry Satuan (Celana)',
                'type' => Service::TYPE_SATUAN,
                'price_per_item' => 6000,
                'estimated_hours' => 24,
                'discount' => 5,
                'description' => 'Laundry per item celana, diskon 5%',
            ],
            [
                'name' => 'Laundry Ekspres (Baju)',
                'type' => Service::TYPE_EKSPRES,
                'price_per_item' => 8000,
                'estimated_hours' => 3,
                'discount' => 0,
                'description' => 'Laundry ekspres per item baju, selesai dalam 3 jam',
            ],
            [
                'name' => 'Laundry Ekspres (Celana)',
                'type' => Service::TYPE_EKSPRES,
                'price_per_item' => 9000,
                'estimated_hours' => 3,
                'discount' => 0,
                'description' => 'Laundry ekspres per item celana, selesai dalam 3 jam',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create sample transactions
        $service1 = Service::find(1); // Kiloan Reguler
        $service3 = Service::find(3); // Satuan Baju

        // Transaction for pelanggan1
        Transaction::create([
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'customer_id' => $pelanggan1->id,
            'cashier_id' => $kasir1->id,
            'service_id' => $service1->id,
            'weight' => 3.5,
            'price' => $service1->price_per_kg * 3.5,
            'discount' => 0,
            'total_amount' => $service1->price_per_kg * 3.5,
            'status' => Transaction::STATUS_SELESAI,
            'payment_method' => Transaction::PAYMENT_CASH,
            'is_paid' => true,
            'notes' => 'Cucian biasa',
            'received_at' => now()->subDays(3),
            'process_started_at' => now()->subDays(3)->addHours(2),
            'completed_at' => now()->subDays(2),
            'picked_up_at' => now()->subDays(1),
        ]);

        // Transaction for pelanggan2
        Transaction::create([
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'customer_id' => $pelanggan2->id,
            'cashier_id' => $kasir2->id,
            'service_id' => $service3->id,
            'quantity' => 5,
            'price' => $service3->price_per_item * 5,
            'discount' => ($service3->price_per_item * 5) * ($service3->discount / 100),
            'total_amount' => ($service3->price_per_item * 5) - (($service3->price_per_item * 5) * ($service3->discount / 100)),
            'status' => Transaction::STATUS_DALAM_PROSES,
            'payment_method' => Transaction::PAYMENT_TRANSFER,
            'is_paid' => true,
            'notes' => 'Baju kerja',
            'received_at' => now()->subHours(2),
            'process_started_at' => now()->subHours(1),
        ]);

        // More sample transactions
        for ($i = 0; $i < 10; $i++) {
            $service = Service::inRandomOrder()->first();
            $customer = User::where('role', 'pelanggan')->inRandomOrder()->first();
            $kasir = User::where('role', 'kasir')->inRandomOrder()->first();
            
            $weight = $service->type === Service::TYPE_KILOAN ? rand(1, 10) : null;
            $quantity = $service->type !== Service::TYPE_KILOAN ? rand(1, 10) : null;
            
            $basePrice = $service->type === Service::TYPE_KILOAN 
                ? $service->price_per_kg * $weight 
                : $service->price_per_item * $quantity;
            
            $discount = $basePrice * ($service->discount / 100);
            $total = $basePrice - $discount;
            
            $statuses = [
                Transaction::STATUS_DITERIMA,
                Transaction::STATUS_DALAM_PROSES,
                Transaction::STATUS_SELESAI,
                Transaction::STATUS_DIAMBIL,
            ];
            
            $status = $statuses[array_rand($statuses)];
            
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'customer_id' => $customer->id,
                'cashier_id' => $kasir->id,
                'service_id' => $service->id,
                'weight' => $weight,
                'quantity' => $quantity,
                'price' => $basePrice,
                'discount' => $discount,
                'total_amount' => $total,
                'status' => $status,
                'payment_method' => $i % 2 == 0 ? Transaction::PAYMENT_CASH : Transaction::PAYMENT_TRANSFER,
                'is_paid' => $status === Transaction::STATUS_DIAMBIL || $i % 3 != 0,
                'notes' => $i % 3 == 0 ? 'Catatan sample ' . ($i + 1) : null,
                'received_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
            ]);
            
            // Set timestamps based on status
            if ($status !== Transaction::STATUS_DITERIMA) {
                $transaction->process_started_at = $transaction->received_at->addHours(rand(1, 3));
            }
            
            if ($status === Transaction::STATUS_SELESAI || $status === Transaction::STATUS_DIAMBIL) {
                $transaction->completed_at = $transaction->process_started_at->addHours(rand(12, 24));
            }
            
            if ($status === Transaction::STATUS_DIAMBIL) {
                $transaction->picked_up_at = $transaction->completed_at->addHours(rand(1, 48));
            }
            
            $transaction->save();
        }
    }
}