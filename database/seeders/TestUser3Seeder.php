<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\CarbonImmutable;
use App\Models\{User, Stage, Deal};

class TestUser3Seeder extends Seeder
{
    public function run(): void
    {
        // Логин в системе — email.
        // В проекте ожидается test_user@crm.local.
        $targetEmail = 'test_user@crm.local';
        $legacyEmail = 'test_user@example.com';

        $user = User::where('email', $targetEmail)->first();
        if (!$user) {
            $legacy = User::where('email', $legacyEmail)->first();
            if ($legacy) {
                // Мигрируем старого тест-юзера на правильный домен, чтобы не плодить пользователей.
                $legacy->email = $targetEmail;
                $legacy->saveQuietly();
                $user = $legacy;
            }
        }

        if (!$user) {
            $user = User::create([
                'name' => 'Тестовый пользователь 3',
                'email' => $targetEmail,
                'password' => Hash::make('testcrm123'),
                'is_admin' => false,
                'role' => 'user',
            ]);
        }

        // Если юзер уже существовал — гарантируем пароль/роль (чтобы можно было зайти).
        $user->forceFill([
            'name' => $user->name ?: 'Тестовый пользователь 3',
            'password' => Hash::make('testcrm123'),
            'is_admin' => false,
            'role' => $user->role ?: 'user',
        ])->saveQuietly();

        $wonStage = Stage::firstOrCreate(['name' => 'Выиграно'], ['order' => 4]);

        $year = (int) CarbonImmutable::now()->year;
        $targets = [
            ['month' => 11, 'day' => 12, 'title' => 'Закрытая сделка (Ноябрь) #1', 'amount' => 120000],
            ['month' => 12, 'day' => 5,  'title' => 'Закрытая сделка (Декабрь) #1', 'amount' => 310000],
            ['month' => 12, 'day' => 18, 'title' => 'Закрытая сделка (Декабрь) #2', 'amount' => 490000],
        ];

        foreach ($targets as $t) {
            $dt = CarbonImmutable::create($year, $t['month'], $t['day'], 12, 0, 0);

            $deal = Deal::firstOrCreate(
                ['user_id' => $user->id, 'title' => $t['title']],
                [
                    // Важно: НЕ создаем тестовую компанию/контакт. Поля nullable.
                    'company_id' => null,
                    'contact_id' => null,
                    'stage_id' => $wonStage->id,
                    'amount' => (float) $t['amount'],
                    'currency' => 'RUB',
                    'expected_close_date' => $dt->toDateString(),
                    'description' => 'Автосоздано для тестового пользователя 3 (закрытая сделка).',
                ]
            );

            // Проставляем даты так, чтобы сделки попали в нужные месяцы в отчетах/графиках.
            $deal->created_at = $dt;
            $deal->updated_at = $dt;
            $deal->stage_id = $wonStage->id;
            $deal->expected_close_date = $dt->toDateString();
            $deal->user_id = $user->id;
            $deal->saveQuietly();
        }
    }
}


