<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $positions = ['Менеджер', 'Директор', 'Специалист', 'Руководитель отдела', 'Генеральный директор'];
        $domains = ['mail.ru', 'gmail.com', 'yandex.ru'];
        $maleFirstNames = ['Иван', 'Петр', 'Александр', 'Дмитрий', 'Сергей', 'Андрей', 'Михаил', 'Владимир', 'Николай', 'Алексей'];
        $femaleFirstNames = ['Мария', 'Анна', 'Елена', 'Ольга', 'Наталья', 'Екатерина', 'Татьяна', 'Ирина', 'Светлана', 'Юлия'];
        $maleLastNames = ['Иванов', 'Петров', 'Сидоров', 'Смирнов', 'Попов', 'Лебедев', 'Козлов', 'Новиков', 'Морозов', 'Петров'];
        $femaleLastNames = ['Иванова', 'Петрова', 'Сидорова', 'Смирнова', 'Попова', 'Лебедева', 'Козлова', 'Новикова', 'Морозова', 'Петрова'];
        
        $isMale = rand(0, 1) === 1;
        $firstName = $isMale ? $maleFirstNames[array_rand($maleFirstNames)] : $femaleFirstNames[array_rand($femaleFirstNames)];
        $lastName = $isMale ? $maleLastNames[array_rand($maleLastNames)] : $femaleLastNames[array_rand($femaleLastNames)];
        
        // Простая транслитерация для email
        $translitMap = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
            'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '',
            'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
        ];
        $translit = function($str) use ($translitMap) {
            $str = mb_strtolower($str, 'UTF-8');
            return strtr($str, $translitMap);
        };
        
        $emailBase = $translit($firstName) . '.' . $translit($lastName) . rand(100, 999);
        
        return [
            'company_id' => Company::factory(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $emailBase . '@' . $domains[array_rand($domains)],
            'phone' => '+7' . $this->faker->numerify('##########'),
            'position' => $this->faker->randomElement($positions),
        ];
    }
}
