<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeInformationSeeder extends Seeder
{
    public function run()
    {
        $this->migrateCustomers();
    }

    private function migrateCustomers()
    {
        $customers = DB::table('oc_customer')->get();
        $totalCustomers = $customers->count();
        $current = 0;

        foreach ($customers as $customer) {
            // Step 1: Insert into member and get new member_id
            $member = DB::table('member')->where('email', $customer->email)->first();
                if (!$member) {
                    $member_id = DB::table('member')->insertGetId([
                        'email' => $customer->email,
                        'password' => $customer->password,
                        'mobile_phone' => $customer->telephone,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'created_by' => 1,
                        'updated_by' => 1
                    ]);
                } else {
                    $member_id = $member->id; // ใช้ id เดิมถ้ามีอยู่แล้ว
                    echo "\n[Duplicate Found] Email already exists: {$customer->email}\n";
                }

            // Step 2: Insert into member_information
            DB::table('member_infomation')->insert([
                'member_id' => $member_id,
                'first_name' => $customer->firstname,
                'last_name' => $customer->lastname,
                'company' => $customer->company,
                'line_id' => $customer->line,
                'vat_register_number' => $customer->vat_register_no,
                'account_type' => $customer->account_type,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1
            ]);

            // Step 3: Insert into member_to_group
            DB::table('member_to_group')->insert([
                'member_id' => $member_id,
                'member_group_id' => $customer->customer_group_id,
            ]);

            // Show progress
            $current++;
            $this->showProgress($current, $totalCustomers, 'Migrate Customers');
        }
    }

    /**
     * แสดงความคืบหน้าของ Migration
     */
    private function showProgress($current, $total, $taskName)
    {
        $percent = round(($current / $total) * 100, 2);
        echo "\r{$taskName}: {$current}/{$total} ({$percent}%)";
        if ($current == $total) {
            echo "\n";
        }
    }
}