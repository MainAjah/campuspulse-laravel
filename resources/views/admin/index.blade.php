@extends('layouts.app')

@section('title', ' - Admin Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-primary">Admin Panel</h1>
            <p class="text-secondary mt-1">Kelola fasilitas, moderasi laporan, dan lihat histori tindakan</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline">Kembali ke Dashboard</a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="card overflow-hidden">
        <div class="border-b border-border">
            <nav class="flex -mb-px" aria-label="Admin tabs">
                <a href="{{ route('admin', ['tab' => 'places']) }}" class="admin-tab px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'places' ? 'border-accent text-accent' : 'border-transparent text-secondary hover:text-primary' }}">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Kelola Fasilitas
                </a>
                <a href="{{ route('admin', ['tab' => 'reports']) }}" class="admin-tab px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'reports' ? 'border-accent text-accent' : 'border-transparent text-secondary hover:text-primary' }}">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Moderasi Laporan
                </a>
                <a href="{{ route('admin', ['tab' => 'history']) }}" class="admin-tab px-6 py-3 text-sm font-medium border-b-2 {{ $tab === 'history' ? 'border-accent text-accent' : 'border-transparent text-secondary hover:text-primary' }}">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Histori Moderasi
                </a>
            </nav>
        </div>

        <div class="p-6">
            @if ($tab === 'places')
                @include('admin.places')
            @elseif ($tab === 'reports')
                @include('admin.reports')
            @elseif ($tab === 'history')
                @include('admin.history')
            @else
                @include('admin.stats')
            @endif
        </div>
    </div>
</div>
@endsection