/**
 * クリップボードへのコピー。
 * [data-copy] ボタンを押すと、data-copy-target で指定した要素の文字（textContent か value）をコピーする。
 */
export default function initCopyButtons() {
    const buttons = document.querySelectorAll('[data-copy]');
    if (!buttons.length) return;

    buttons.forEach((btn) => {
        btn.addEventListener('click', async () => {
            const target = document.querySelector(btn.dataset.copy);
            if (!target) return;
            const text = 'value' in target && target.value !== undefined
                ? target.value
                : target.textContent;

            try {
                await navigator.clipboard.writeText((text || '').trim());
                const label = btn.querySelector('[data-copy-label]') || btn;
                const original = label.textContent;
                label.textContent = 'コピーしました';
                btn.dataset.copied = 'true';
                setTimeout(() => {
                    label.textContent = original;
                    delete btn.dataset.copied;
                }, 1500);
            } catch (e) {
                /* クリップボード非対応環境では何もしない */
            }
        });
    });
}
