<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Deal;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'deal_id' => Deal::factory(),
            'contact_id' => Contact::factory(),
            'type' => $this->faker->randomElement(['call', 'meeting', 'email', 'task']),
            'subject' => $this->faker->randomElement([
                'Звонок клиенту', 'Встреча с партнером', 'Отправка коммерческого предложения', 'Подготовка договора',
                'Презентация продукта', 'Обсуждение условий', 'Согласование сроков', 'Контроль выполнения',
                'Переговоры по цене', 'Уточнение деталей', 'Планирование встречи', 'Отчет о проделанной работе'
            ]),
            'due_at' => $this->faker->dateTimeBetween('now', '+30 days'),
            'done' => $this->faker->boolean(30),
        ];
    }
}
