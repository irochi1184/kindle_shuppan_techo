<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    /** 認可はルートの auth ミドルウェアとコントローラの Policy で担保する */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 本の作成・更新で共通のバリデーションルール。
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'target_reader' => ['nullable', 'string'],
            'book_goal' => ['nullable', 'string'],
            'reader_benefit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            // 状態は定義済みの値のみ許可する
            'status' => ['nullable', Rule::in(array_keys(Book::STATUSES))],
            // 表紙画像（任意）。JPEG/PNG、5MBまで
            'cover' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
            'remove_cover' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['title' => '本のタイトル'];
    }
}
