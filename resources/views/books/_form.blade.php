@php($book = $book ?? null)
@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">
            本のタイトル <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}"
               class="{{ $inputClass }}" placeholder="例：令和のインストラクショナルデザイン" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">サブタイトル</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $book->subtitle ?? '') }}"
               class="{{ $inputClass }}" placeholder="例：生成AI時代の学びの設計図">
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">著者名</label>
        <input type="text" name="author_name" value="{{ old('author_name', $book->author_name ?? '') }}"
               class="{{ $inputClass }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">想定読者</label>
        <textarea name="target_reader" rows="2" class="{{ $inputClass }}"
                  placeholder="どんな人に読んでほしいですか？">{{ old('target_reader', $book->target_reader ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">本の目的</label>
        <textarea name="book_goal" rows="2" class="{{ $inputClass }}"
                  placeholder="この本で何を伝えたいですか？">{{ old('book_goal', $book->book_goal ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">読者が得るもの</label>
        <textarea name="reader_benefit" rows="2" class="{{ $inputClass }}"
                  placeholder="読み終えたとき、読者はどうなっていますか？">{{ old('reader_benefit', $book->reader_benefit ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">販売説明文</label>
        <textarea name="description" rows="4" class="{{ $inputClass }}"
                  placeholder="ストアに表示する紹介文（あとで出版情報からも編集できます）">{{ old('description', $book->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">状態</label>
        <select name="status" class="{{ $inputClass }}">
            @foreach (\App\Models\Book::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $book->status ?? 'planning') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1.5">表紙画像</label>
        <div class="flex items-start gap-4">
            @if (($book->coverUrl() ?? null))
                <img src="{{ $book->coverUrl() }}" alt="現在の表紙" class="w-20 h-28 rounded-lg object-cover border border-stone-200 shadow-sm">
            @else
                <div class="w-20 h-28 rounded-lg border border-dashed border-stone-300 flex items-center justify-center text-stone-300">
                    <x-icon name="document" class="w-6 h-6" />
                </div>
            @endif
            <div class="flex-1">
                <input type="file" name="cover" accept="image/png,image/jpeg"
                       class="block w-full text-sm text-stone-600 file:mr-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-stone-700 hover:file:bg-stone-200 cursor-pointer">
                <p class="text-xs text-stone-400 mt-1.5">JPEG または PNG・5MBまで。縦長（おすすめ 1600×2560px）。</p>
                @if (($book->cover_path ?? null))
                    <label class="inline-flex items-center gap-2 mt-2 text-sm text-stone-600">
                        <input type="checkbox" name="remove_cover" value="1" class="rounded border-stone-300 text-red-600 focus:ring-red-500/30">
                        現在の表紙を削除する
                    </label>
                @endif
            </div>
        </div>
    </div>
</div>
