<?php

namespace App\Http\Controllers;

use App\Models\{Company, Contact, Deal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('crm.import');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'type' => 'required|in:companies,contacts,deals'
        ]);
        
        $file = $request->file('file');
        $type = $request->type;
        $content = file_get_contents($file->getRealPath());
        $lines = array_filter(explode("\n", $content), fn($l) => !empty(trim($l)));
        
        if (empty($lines)) {
            return back()->withErrors(['error' => 'Файл пуст']);
        }
        
        $headers = str_getcsv(array_shift($lines));
        if (empty($headers)) {
            return back()->withErrors(['error' => 'Неверный формат CSV']);
        }
        
        $imported = 0;
        $errors = [];
        
        DB::beginTransaction();
        try {
            foreach ($lines as $index => $line) {
                if (empty(trim($line))) continue;
                
                $data = str_getcsv($line);
                if (count($data) < count($headers)) {
                    $errors[] = "Строка " . ($index + 2) . ": недостаточно данных";
                    continue;
                }
                
                $row = @array_combine($headers, $data);
                if (!$row) {
                    $errors[] = "Строка " . ($index + 2) . ": ошибка парсинга";
                    continue;
                }
                
                try {
                    if ($type === 'companies') {
                        Company::create([
                            'name' => $row['name'] ?? '',
                            'email' => $row['email'] ?? null,
                            'phone' => $row['phone'] ?? null,
                            'website' => $row['website'] ?? null,
                            'industry' => $row['industry'] ?? null,
                            'city' => $row['city'] ?? null,
                            'country' => $row['country'] ?? null,
                            'address' => $row['address'] ?? null,
                        ]);
                    } elseif ($type === 'contacts') {
                        Contact::create([
                            'company_id' => $row['company_id'] ?? null,
                            'first_name' => $row['first_name'] ?? '',
                            'last_name' => $row['last_name'] ?? null,
                            'email' => $row['email'] ?? null,
                            'phone' => $row['phone'] ?? null,
                            'position' => $row['position'] ?? null,
                        ]);
                    } elseif ($type === 'deals') {
                        Deal::create([
                            'company_id' => $row['company_id'] ?? null,
                            'contact_id' => $row['contact_id'] ?? null,
                            'stage_id' => $row['stage_id'] ?? null,
                            'title' => $row['title'] ?? '',
                            'amount' => $row['amount'] ?? null,
                            'currency' => $row['currency'] ?? 'RUB',
                            'expected_close_date' => $row['expected_close_date'] ?? null,
                            'description' => $row['description'] ?? null,
                        ]);
                    }
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Строка " . ($index + 2) . ": " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка импорта: ' . $e->getMessage()]);
        }
        
        return back()->with('success', "Импортировано: $imported. Ошибок: " . count($errors));
    }
}

