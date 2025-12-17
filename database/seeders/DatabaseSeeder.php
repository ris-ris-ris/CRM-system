<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Stage;
use Database\Seeders\TestUser3Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Администратор',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'admin',
            ]);
        }
        
        // Создаем тестового пользователя
        if (!User::where('email', 'user@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Тестовый пользователь',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'user',
            ]);
        }

        // Второй тестовый пользователь (для аналитики по сотрудникам)
        if (!User::where('email', 'user2@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Тестовый пользователь 2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'user',
            ]);
        }
        
        // Создаем менеджера
        if (!User::where('email', 'manager@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Менеджер',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'manager',
            ]);
        }

        $stages = ['Лид','Квалификация','Предложение','Переговоры','Выиграно','Проиграно'];
        foreach ($stages as $i => $name) {
            Stage::firstOrCreate(['name' => $name], ['order' => $i]);
        }

        // Тестовый пользователь 3 + закрытые сделки за ноябрь/декабрь
        $this->call(TestUser3Seeder::class);
        
        $this->call(DataSeeder::class);
    }
}
