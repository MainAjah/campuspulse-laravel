@props(['report'])

@php
    $freshness = $report->freshness ?? 'FRESH';
    $freshnessConfig = [
        'FRESH' => ['label' => 'Segar', 'class' => 'bg-green-100 text-green-800 border-green-200', 'icon' => '🟢'],
        'AGED' => ['label' => 'Mulai Usang', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200', 'icon' => '🟡'],
        'EXPIRED' => ['label' => 'Kedaluwarsa', 'class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => '🔴'],
    ];
    $config = $freshnessConfig[$freshness] ?? ['label' => $freshness, 'class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => '⚪'];
@endphp

<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['class'] }}">
    <span aria-hidden="true">{{ $config['icon'] }}</span>
    {{ $config['label'] }}
</span>