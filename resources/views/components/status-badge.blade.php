@props(['status', 'category'])

@php
    $statusConfig = [
        'PENDING' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'VERIFIED' => ['label' => 'Terverifikasi', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'RESOLVED' => ['label' => 'Terselesaikan', 'class' => 'bg-green-100 text-green-800 border-green-200'],
        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];
    $config = $statusConfig[$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-800 border-gray-200'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['class'] }}">
    {{ $config['label'] }}
</span>