@extends('layouts.app')

@section('title', ' - Daftar')

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
            <h1 class="text-2xl font-bold text-primary mt-6">Buat Akun Baru</h1>
            <p class="text-secondary mt-2">Bergabung dengan komunitas mahasiswa CampusPulse</p>
        </div>

        <div class="card p-8">
            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-6" id="register-form">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirectTo }}">

                <div>
                    <label for="name" class="block text-sm font-medium text-secondary mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="input w-full py-2.5" required autocomplete="name" autofocus maxlength="100">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-secondary mb-1">Email</label>
                    <input type="email" name="email" id="email" class="input w-full py-2.5" required autocomplete="email">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-secondary mb-1">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="input w-full py-2.5" required autocomplete="new-password" minlength="8">
                    <p class="mt-1 text-xs text-secondary">Minimal 8 karakter</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-secondary mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input w-full py-2.5" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full py-2.5 text-lg">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-secondary">Sudah punya akun? <a href="{{ route('login', ['redirect' => $redirectTo]) }}" class="text-accent hover:underline font-medium">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</div>
@endsection