@php($book = $book ?? null)
@php($inputClass = 'w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none')

<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">
            本のタイトル <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}"
               class="{{ $inputClass }}" placeholder="例：令和のインストラクショナルデザイン" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">サブタイトル</label>
        <input type="text" name="subtitle" value="{{ old('subtitle', $book->subtitle ?? '') }}"
               class="{{ $inputClass }}" placeholder="例：生成AI時代の学びの設計図">
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">著者名</label>
        <input type="text" name="author_name" value="{{ old('author_name', $book->author_name ?? '') }}"
               class="{{ $inputClass }}">
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">想定読者</label>
        <textarea name="target_reader" rows="2" class="{{ $inputClass }}"
                  placeholder="どんな人に読んでほしいですか？">{{ old('target_reader', $book->target_reader ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">本の目的</label>
        <textarea name="book_goal" rows="2" class="{{ $inputClass }}"
                  placeholder="この本で何を伝えたいですか？">{{ old('book_goal', $book->book_goal ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">読者が得るもの</label>
        <textarea name="reader_benefit" rows="2" class="{{ $inputClass }}"
                  placeholder="読み終えたとき、読者はどうなっていますか？">{{ old('reader_benefit', $book->reader_benefit ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">販売説明文</label>
        <textarea name="description" rows="4" class="{{ $inputClass }}"
                  placeholder="ストアに表示する紹介文（あとで出版情報からも編集できます）">{{ old('description', $book->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">状態</label>
        <select name="status" class="{{ $inputClass }}">
            @foreach (\App\Models\Book::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $book->status ?? 'planning') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
