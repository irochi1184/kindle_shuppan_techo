@php($chapter = $chapter ?? null)
@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">
            章のタイトル <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $chapter->title ?? '') }}"
               class="{{ $inputClass }}" placeholder="例：第1章 学びの設計とは" required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">並び順</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $chapter->sort_order ?? '') }}"
                   class="{{ $inputClass }}" placeholder="空欄なら末尾に追加">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1.5">状態</label>
            <select name="status" class="{{ $inputClass }}">
                @foreach (\App\Models\Chapter::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $chapter->status ?? 'not_started') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">概要</label>
        <textarea name="summary" rows="2" class="{{ $inputClass }}"
                  placeholder="この章で書くことのメモ">{{ old('summary', $chapter->summary ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">原稿</label>
        <textarea name="body" rows="18"
                  class="w-full rounded-xl border border-stone-300 bg-stone-50/50 px-4 py-3 text-sm leading-relaxed font-mono transition focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400"
                  placeholder="ここに本文を書きます。">{{ old('body', $chapter->body ?? '') }}</textarea>
        <p class="text-xs text-stone-400 mt-1">保存するたびに文字数を自動で数え、保存履歴に残します。</p>
    </div>
</div>
