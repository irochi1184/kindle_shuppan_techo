<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /** 認証画面（ログイン/登録など）用のレイアウトを描画する */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
