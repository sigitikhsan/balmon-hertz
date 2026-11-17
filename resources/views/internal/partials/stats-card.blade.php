<div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
    <div class="flex items-start justify-between mb-2">
        <h3 class="text-sm font-medium text-slate-600">{{ $title }}</h3>
        @isset($icon)
            <div class="w-8 h-8 {{ $bgColor ?? 'bg-blue-50' }} rounded-lg flex items-center justify-center">
                <i class="fa-solid {{ $icon }} {{ $textColor ?? 'text-blue-600' }}"></i>
            </div>
        @endisset
    </div>
    <div class="mt-3">
        <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
        @isset($change)
            <p class="text-xs mt-1 {{ $changeColor ?? 'text-green-600' }}">
                <i class="fa-solid fa-{{ $changeIcon ?? 'arrow-up-right' }} mr-1"></i>
                {{ $change }}
            </p>
        @endisset
    </div>
</div>