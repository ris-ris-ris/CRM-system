<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Импорт данных
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Импорт из CSV</h3>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                @endif
                
                <form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Тип данных</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="companies">Компании</option>
                            <option value="contacts">Контакты</option>
                            <option value="deals">Сделки</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CSV файл</label>
                        <input type="file" name="file" accept=".csv,.txt" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"><strong>Формат CSV для компаний:</strong></p>
                        <code class="text-xs">name,email,phone,website,industry,city,country,address</code>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4 mb-2"><strong>Формат CSV для контактов:</strong></p>
                        <code class="text-xs">company_id,first_name,last_name,email,phone,position</code>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4 mb-2"><strong>Формат CSV для сделок:</strong></p>
                        <code class="text-xs">company_id,contact_id,stage_id,title,amount,currency,expected_close_date,description</code>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Импортировать</button>
                </form>
                
                <div class="mt-8">
                    <h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">Генерация тестовых данных</h4>
                    <form method="POST" action="{{ route('import.generate') }}" class="space-y-2">
                        @csrf
                        <div class="flex gap-2">
                            <input type="number" name="count" value="10" min="1" max="100" class="px-3 py-2 border border-gray-300 rounded-lg w-32">
                            <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="companies">Компаний</option>
                                <option value="contacts">Контактов</option>
                                <option value="deals">Сделок</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Сгенерировать</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


