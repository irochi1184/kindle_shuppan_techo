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
        <div class="flex items-end justify-between mb-1.5">
            <label class="block text-sm font-medium text-stone-700">原稿</label>
            <div class="flex items-center gap-3 text-xs text-stone-400">
                <span><span data-word-count class="font-medium text-stone-600 tabular-nums">0</span> 字</span>
                @isset($autosaveUrl)
                    <span data-autosave-status data-tone="muted">自動保存：入力すると数秒後に保存されます</span>
                @endisset
            </div>
        </div>

        {{-- エディタ（左）＋ Markdownライブプレビュー（右） --}}
        <div class="grid lg:grid-cols-2 gap-3">
            <textarea name="body" rows="20"
                      data-editor-body
                      data-autosave-url="{{ $autosaveUrl ?? '' }}"
                      class="w-full rounded-xl border border-stone-300 bg-stone-50/50 px-4 py-3 text-sm leading-relaxed font-mono transition focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400"
                      placeholder="ここに本文を書きます。Markdown（## 見出し、- 箇条書き など）が使えます。">{{ old('body', $chapter->body ?? '') }}</textarea>

            <div class="rounded-xl border border-stone-200 bg-white px-5 py-4 overflow-auto" style="max-height: 30rem">
                <p class="text-[11px] font-medium text-stone-400 mb-2 uppercase tracking-wide">プレビュー</p>
                <div data-md-preview class="prose-editor text-[15px] leading-relaxed text-stone-700"></div>
            </div>
        </div>
        <p class="text-xs text-stone-400 mt-1.5">Markdownで書けます。明示的に「保存」すると保存履歴（版）に残ります。</p>
    </div>
</div>
