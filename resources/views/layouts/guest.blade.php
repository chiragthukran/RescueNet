<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RescueNet') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="font-family: 'Inter', sans-serif; background: #fdfbf7; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem;">
        <div style="margin-bottom: 2rem;">
            <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #d97706, #b45309); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🛡️</div>
                <span style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #d97706, #b45309); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">RescueNet</span>
            </a>
        </div>

        <div style="width: 100%; max-width: 440px; background: rgba(255,255,255,0.8); backdrop-filter: blur(16px); border: 1px solid #e6dace; border-radius: 1rem; padding: 2rem; box-shadow: 0 8px 32px rgba(138,123,123,0.15);">
            {{ $slot }}
        </div>
    </body>
</html>
