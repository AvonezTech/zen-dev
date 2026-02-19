<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'Test Client',
            'contact_name' => 'Test Name',
            'contact_email' => 'test@client.com',
            'contact_number' => '98766543210',
        ]);
    }
}
