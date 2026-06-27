import Sortable from 'sortablejs';

/**
 * 書籍詳細の章リストをドラッグ&ドロップで並べ替える。
 * 並べ替え後は新しい順序をサーバーへ送り、sort_order を保存する。
 */
export default function initChapterSort() {
    const list = document.querySelector('[data-sortable-chapters]');
    if (!list) return;

    const reorderUrl = list.dataset.reorderUrl || '';
    const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
    const statusEl = document.querySelector('[data-reorder-status]');

    Sortable.create(list, {
        handle: '[data-drag-handle]',
        animation: 150,
        ghostClass: 'opacity-40',
        onEnd: async () => {
            const ids = Array.from(list.querySelectorAll('[data-chapter-id]'))
                .map((el) => el.dataset.chapterId);

            // 表示中の番号バッジを振り直す
            list.querySelectorAll('[data-chapter-order]').forEach((el, i) => {
                el.textContent = i + 1;
            });

            if (statusEl) statusEl.textContent = '並び順を保存中…';
            try {
                const res = await fetch(reorderUrl, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ chapters: ids }),
                });
                if (!res.ok) throw new Error('reorder failed');
                if (statusEl) statusEl.textContent = '並び順を保存しました';
            } catch (e) {
                if (statusEl) statusEl.textContent = '並び順の保存に失敗しました';
            }
        },
    });
}
