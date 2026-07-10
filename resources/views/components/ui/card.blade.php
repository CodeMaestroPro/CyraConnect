<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800']) }}>
    @isset($title)
        <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
    @endisset
    {{ $slot }}
</div>
