<?php

namespace App\Http\Controllers;

use App\Models\{Company, Contact, Deal, Stage};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenerateDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:100',
            'type' => 'required|in:companies,contacts,deals'
        ]);
        
        $count = $request->count;
        $type = $request->type;
        
        $companies = Company::pluck('id')->toArray();
        $contacts = Contact::pluck('id')->toArray();
        $stages = Stage::pluck('id')->toArray();
        
        for ($i = 0; $i < $count; $i++) {
            if ($type === 'companies') {
                Company::create([
                    'name' => 'Компания ' . Str::random(8),
                    'email' => 'company' . $i . '@example.com',
                    'phone' => '+7' . rand(9000000000, 9999999999),
                    'website' => 'https://example' . $i . '.com',
                    'industry' => ['IT', 'Продажи', 'Производство', 'Услуги'][rand(0, 3)],
                    'city' => ['Москва', 'СПб', 'Казань', 'Екатеринбург'][rand(0, 3)],
                    'country' => 'Россия',
                    'address' => 'ул. Примерная, д. ' . rand(1, 100),
                ]);
            } elseif ($type === 'contacts') {
                if (empty($companies)) {
                    $company = Company::create([
                        'name' => 'Компания для контакта',
                        'email' => 'temp@example.com',
                    ]);
                    $companies[] = $company->id;
                }
                Contact::create([
                    'company_id' => $companies[array_rand($companies)],
                    'first_name' => ['Иван', 'Мария', 'Петр', 'Анна'][rand(0, 3)],
                    'last_name' => ['Иванов', 'Петрова', 'Сидоров', 'Козлова'][rand(0, 3)],
                    'email' => 'contact' . $i . '@example.com',
                    'phone' => '+7' . rand(9000000000, 9999999999),
                    'position' => ['Менеджер', 'Директор', 'Специалист'][rand(0, 2)],
                ]);
            } elseif ($type === 'deals') {
                if (empty($companies)) {
                    $company = Company::create(['name' => 'Компания для сделки', 'email' => 'temp@example.com']);
                    $companies[] = $company->id;
                }
                if (empty($stages)) {
                    Stage::firstOrCreate(['name' => 'Lead'], ['order' => 0]);
                    $stages = Stage::pluck('id')->toArray();
                }
                Deal::create([
                    'company_id' => $companies[array_rand($companies)],
                    'contact_id' => !empty($contacts) ? $contacts[array_rand($contacts)] : null,
                    'stage_id' => $stages[array_rand($stages)],
                    'title' => 'Сделка ' . Str::random(6),
                    'amount' => rand(10000, 1000000),
                    'currency' => 'RUB',
                    'expected_close_date' => now()->addDays(rand(1, 90))->format('Y-m-d'),
                    'description' => 'Описание сделки ' . $i,
                ]);
            }
        }
        
        return back()->with('success', "Сгенерировано $count записей типа: $type");
    }
}


