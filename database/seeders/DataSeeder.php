<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Company, Contact, Deal, Stage, Activity, Note, User};
use Illuminate\Support\Str;
use Carbon\CarbonImmutable;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $stages = Stage::all();
        if ($stages->isEmpty()) return;
        
        // Создаем компании
        if (Company::count() < 50) {
            $companies = Company::factory(50 - Company::count())->create();
        }
        $companies = Company::all();
        
        // Создаем контакты
        if (Contact::count() < 100) {
            Contact::factory(100 - Contact::count())->create([
                'company_id' => fn() => $companies->random()->id
            ]);
        }
        $contacts = Contact::all();
        
        // Создаем сделки
        if (Deal::count() < 50) {
            Deal::factory(50 - Deal::count())->create([
                'company_id' => fn() => $companies->random()->id,
                'contact_id' => fn() => $contacts->random()->id,
                'stage_id' => fn() => $stages->random()->id,
            ]);
        }
        $deals = Deal::all();

        // Распределяем сделки по датам с начала ноября (для графиков/отчетов).
        $start = CarbonImmutable::now()->startOfYear()->addMonths(10)->startOfDay(); // 1 Nov текущего года
        if (CarbonImmutable::now()->lessThan($start)) {
            $start = CarbonImmutable::now()->subMonth()->startOfDay();
        }

        $wonStage = $stages->firstWhere('name', 'Выиграно');
        $lostStage = $stages->firstWhere('name', 'Проиграно');
        $midStages = $stages->filter(function ($s) use ($wonStage, $lostStage) {
            return ($wonStage ? $s->id !== $wonStage->id : true)
                && ($lostStage ? $s->id !== $lostStage->id : true);
        })->values();

        $user1 = User::where('email', 'user@example.com')->first();
        $user2 = User::where('email', 'user2@example.com')->first();
        // менеджер не должен закрывать сделки — не используем его в назначениях

        foreach ($deals as $deal) {
            $createdAt = CarbonImmutable::instance(fake()->dateTimeBetween($start, 'now'));
            $updatedAt = $createdAt->addDays(random_int(0, 10));

            $deal->created_at = $createdAt;
            $deal->updated_at = $updatedAt;

            // Назначаем исполнителя (чтобы график "по сотрудникам" был наглядный).
            // Назначаем только обычных пользователей (user/user2).
            $assignee = null;
            $pick = random_int(1, 100);
            if ($user2 && $pick <= 45) {
                $assignee = $user2;
            } elseif ($user1) {
                $assignee = $user1;
            }
            if ($assignee) {
                $deal->user_id = $assignee->id;
            }

            // Закрытые сделки + суммы. user2 чаще "Выиграно" и суммы чуть выше.
            $roll = random_int(1, 100);
            $isUser2 = $assignee && $user2 && $assignee->id === $user2->id;
            $winChance = $isUser2 ? 45 : 30;
            $loseChance = $isUser2 ? 15 : 10;

            if ($wonStage && $roll <= $winChance) {
                $deal->stage_id = $wonStage->id;
            } elseif ($lostStage && $roll <= ($winChance + $loseChance)) {
                $deal->stage_id = $lostStage->id;
            } elseif ($midStages->isNotEmpty()) {
                $deal->stage_id = $midStages->random()->id;
            }

            if ($isUser2) {
                $deal->amount = max((float) $deal->amount, (float) random_int(800000, 6000000));
            }

            $deal->saveQuietly();
        }

        // Гарантируем, что у обоих юзеров есть выигранные сделки (для демо аналитики)
        if ($wonStage) {
            foreach ([[$user1, 6], [$user2, 10]] as [$u, $need]) {
                if (!$u) continue;
                $have = Deal::where('user_id', $u->id)->where('stage_id', $wonStage->id)->count();
                if ($have >= $need) continue;
                $toFix = Deal::where('user_id', $u->id)
                    ->where('stage_id', '!=', $wonStage->id)
                    ->inRandomOrder()
                    ->limit($need - $have)
                    ->get();
                foreach ($toFix as $d) {
                    $d->stage_id = $wonStage->id;
                    $d->saveQuietly();
                }
            }
        }
        
        // Создаем активности
        if (Activity::count() < 80) {
            Activity::factory(80 - Activity::count())->create([
                'deal_id' => fn() => $deals->random()->id,
                'contact_id' => fn() => $contacts->random()->id,
            ]);
        }
        
        // Создаем заметки
        if (Note::count() < 60) {
            $noteTexts = [
                'Важная информация по сделке. Клиент заинтересован в продукте. Требуется дополнительная встреча.',
                'Клиент запросил коммерческое предложение. Отправить до конца недели.',
                'Проведена встреча с клиентом. Обсудили условия сотрудничества. Ожидаем ответ.',
                'Клиент рассматривает предложение. Планируем звонок через 3 дня.',
                'Требуется уточнение технических деталей. Назначена встреча на следующей неделе.',
                'Компания активно развивается. Потенциальный крупный клиент. Необходимо поддерживать контакт.',
                'Клиент заинтересован в долгосрочном сотрудничестве. Обсудить условия договора.',
                'Требуется подготовка презентации продукта. Встреча запланирована.',
            ];
            foreach ($deals->take(30) as $deal) {
                Note::create([
                    'notable_type' => 'App\\Models\\Deal',
                    'notable_id' => $deal->id,
                    'body' => $noteTexts[array_rand($noteTexts)],
                ]);
            }
            foreach ($companies->take(30) as $company) {
                Note::create([
                    'notable_type' => 'App\\Models\\Company',
                    'notable_id' => $company->id,
                    'body' => $noteTexts[array_rand($noteTexts)],
                ]);
            }
        }
    }
}

