@php
    $categoryColors = [
        'makanan' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
        'kerajinan' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
        'pertanian' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
        'jasa' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'tekstil' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
        'lainnya' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600']
    ];
@endphp

@foreach($categoryStats as $stat)
    @php
        $colors = $categoryColors[$stat->category] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
    @endphp
    <div class="text-center p-3 {{ $colors['bg'] }} rounded-lg">
        <div class="text-xl font-bold {{ $colors['text'] }}">{{ $stat->count }}</div>
        <div class="text-sm text-gray-600">{{ ucfirst($stat->category) }}</div>
    </div>
@endforeach