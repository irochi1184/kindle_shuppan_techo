@props(['color' => 'bg-stone-100 text-stone-600 ring-stone-500/15'])

{{-- 状態表示用のピルバッジ。color には bg/text/ring のTailwindクラスを渡す --}}
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset $color"]) }}>
    {{ $slot }}
</span>
