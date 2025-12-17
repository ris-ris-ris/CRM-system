<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $cities = ['Москва', 'Санкт-Петербург', 'Казань', 'Екатеринбург', 'Новосибирск', 'Нижний Новгород'];
        $industries = ['IT', 'Продажи', 'Производство', 'Услуги', 'Торговля', 'Строительство', 'Финансы'];
        $domains = ['mail.ru', 'gmail.com', 'yandex.ru'];
        $companyNames = ['ТехноПром', 'БизнесСервис', 'ТоргКомплекс', 'ПроизводствоПлюс', 'СтройГрупп', 'ФинансГарант', 'ЛогистикСервис', 'МаркетПлюс', 'КонсалтГрупп', 'ИнновацииПро'];
        
        return [
            'name' => $companyNames[array_rand($companyNames)] . ' ' . ['ООО', 'ЗАО', 'ОАО'][array_rand(['ООО', 'ЗАО', 'ОАО'])],
            'email' => strtolower(str_replace(' ', '', $this->faker->words(2, true))) . '@' . $domains[array_rand($domains)],
            'phone' => '+7' . $this->faker->numerify('##########'),
            'website' => 'https://' . $this->faker->domainName(),
            'industry' => $this->faker->randomElement($industries),
            'city' => $this->faker->randomElement($cities),
            'country' => 'Россия',
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->city(),
        ];
    }
}
