<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Video Manager</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-blue-50">
<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            {{-- Left side --}}
            <div class="flex">
                <a href="{{ url('/') }}" class="flex items-center text-xl font-bold text-gray-700">
                    PHP Video Manager
                </a>
            </div>

            {{-- Search bar --}}
            <div class="flex">
                <livewire:search-bar />
            </div>

            {{-- Right side --}}
            <div class="flex items-center space-x-4">
                {{-- Upload button --}}
                <a href="{{ url('/upload') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition">
                    Upload
                </a>

                @auth
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <div class="container mx-auto mt-6">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
