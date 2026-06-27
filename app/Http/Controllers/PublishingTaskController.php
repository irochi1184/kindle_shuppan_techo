<?php

namespace App\Http\Controllers;

use App\Models\PublishingTask;

class PublishingTaskController extends Controller
{
    /** 出版前チェックリストの「済み / 未」を切り替える */
    public function toggle(PublishingTask $task)
    {
        $this->authorize('update', $task->book);
        $task->update(['is_done' => ! $task->is_done]);

        return redirect()
            ->route('books.show', $task->book_id)
            ->with('status', 'チェックリストを更新しました。');
    }
}
