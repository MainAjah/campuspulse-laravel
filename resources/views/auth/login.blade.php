@extends('layouts.app')

@section('title', ' - Masuk')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 hover:opacity-80 transition-opacity">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent text-lg font-bold text-white">
                    CP
                </span>
                <span class="text-2xl font-semibold tracking-tight text-primary">CampusPulse</span>
            </a>
            <h1 class="text-2xl font-bold text-primary mt-6">Selamat Datang Kembali</h1>
            <p class="text-secondary mt-2">Masuk untuk mengakses dashboard dan fitur lengkap</p>
        </div>

        <div class="card p-8">
            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirectTo }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-secondary mb-1">Email</label>
                    <input type="email" name="email" id="email" class="input w-full py-2.5" required autocomplete="email" autofocus>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-secondary">Kata Sandi</label>
                    </div>
                    <input type="password" name="password" id="password" class="input w-full py-2.5" required autocomplete="current-password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-accent border-border rounded focus:ring-accent">
                        <span class="text-sm text-secondary">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full py-2.5 text-lg">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-secondary">Belum punya akun? <a href="{{ route('register', ['redirect' => $redirectTo]) }}" class="text-accent hover:underline font-medium">Daftar di sini</a></p>
            </div>

            <div class="mt-6 pt-6 border-t border-border">
                <p class="text-xs text-secondary text-center mb-3">Akun Demo (untuk pengujian):</p>
                <div class="space-y-2 text-sm">
                    <div class="p-2 rounded bg-surface border border-border font-mono text-xs">demo@campuspulse.id / demo1234</div>
                    <div class="p-2 rounded bg-surface border border-border font-mono text-xs">admin@campuspulse.id / admin1234</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection