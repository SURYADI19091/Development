@extends('frontend.main')

@section('title', 'Login - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'LOGIN')
@section('header_icon', 'fas fa-sign-in-alt')
@section('header_bg_color', 'bg-blue-600')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <i class="fas fa-user-circle text-6xl text-blue-600 mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-900">Masuk ke Sistem</h2>
            <p class="text-gray-600">Silakan masukkan kredensial Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Masukkan email Anda">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Masukkan password Anda">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500">
                    Lupa password?
                </a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 font-medium">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection