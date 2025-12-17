<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход - CRM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body { margin: 0; padding: 0; overflow-x: hidden; }
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.5) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.5) 0%, transparent 50%),
                radial-gradient(circle at 50% 20%, rgba(236, 72, 153, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 60% 60%, rgba(34, 197, 94, 0.3) 0%, transparent 50%),
                linear-gradient(180deg, #000000 0%, #0a0a0f 50%, #000000 100%);
            background-size: 200% 200%, 200% 200%, 200% 200%, 200% 200%, 100% 100%;
            animation: bgShift 8s ease-in-out infinite;
        }
        @keyframes bgShift {
            0%, 100% { background-position: 0% 50%, 100% 50%, 50% 0%, 40% 60%, 0% 0%; }
            25% { background-position: 25% 25%, 75% 75%, 25% 75%, 60% 40%, 0% 0%; }
            50% { background-position: 50% 50%, 50% 50%, 50% 50%, 50% 50%, 0% 0%; }
            75% { background-position: 75% 75%, 25% 25%, 75% 25%, 30% 70%, 0% 0%; }
        }
        .form-container { position: relative; z-index: 2; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .form-box { 
            background: rgba(0, 0, 0, 0.7); 
            backdrop-filter: blur(20px); 
            padding: 48px; 
            border-radius: 24px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.5); 
            max-width: 440px; 
            width: 100%;
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>
    <div class="form-container">
        <div class="form-box">
            <h2 class="text-4xl font-bold mb-2 text-white text-center" style="font-family: 'Inter', sans-serif;">CRM</h2>
            <p class="text-center text-gray-300 mb-8 text-sm">Вход в систему</p>
            
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input id="email" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com" style="color: #ffffff !important; background-color: rgba(255, 255, 255, 0.1) !important;">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Пароль</label>
                    <input id="password" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" type="password" name="password" required placeholder="••••••••" style="color: #ffffff !important; background-color: rgba(255, 255, 255, 0.1) !important;">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-400 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-300">Запомнить меня</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full text-white py-3 px-4 rounded-xl transition font-semibold shadow-lg" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); box-shadow: 0 10px 40px rgba(239, 68, 68, 0.4);">
                    Войти
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('register') }}" class="text-sm text-blue-400 hover:text-blue-300 transition">Нет аккаунта? Зарегистрироваться</a>
            </div>
        </div>
    </div>
</body>
</html>
