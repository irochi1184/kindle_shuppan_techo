<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class KdpMetadataRequest extends FormRequest
{
    /** 認可はルートの auth ミドルウェアとコントローラの Policy で担保する */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 出版情報（KDP）のバリデーションルール。
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            // キーワードは改行区切り。空行を除いて7件まで（#10: 黙って切り捨てず明示エラー）
            'keywords' => ['nullable', 'string', function (string $attribute, mixed $value, Closure $fail) {
                if (count($this->splitLines((string) $value)) > 7) {
                    $fail('キーワードは7つ以内で入力してください。');
                }
            }],
            'categories' => ['nullable', 'string'],
            'price' => ['nullable', 'integer', 'min:0'],
            'royalty_plan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /** 改行区切りのテキストを、トリム済み・空行除外の配列にする */
    public function splitLines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => $line !== '')
            ->values()
            ->all();
    }
}
