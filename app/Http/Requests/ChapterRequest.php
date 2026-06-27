<?php

namespace App\Http\Requests;

use App\Models\Chapter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChapterRequest extends FormRequest
{
    /** 認可はルートの auth ミドルウェアとコントローラの Policy で担保する */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 章の作成・更新で共通のバリデーションルール。
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            // 状態は定義済みの値のみ許可する
            'status' => ['nullable', Rule::in(array_keys(Chapter::STATUSES))],
            // 保存メモ（保存履歴に残す任意のメモ）
            'version_note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return ['title' => '章のタイトル'];
    }
}
