import { marked } from 'marked';

/**
 * 章エディタの拡張：自動保存・文字数のリアルタイム表示・Markdownライブプレビュー。
 * 編集画面にだけ存在する data 属性を見て動作するため、他の画面では何もしない。
 */
export default function initChapterEditor() {
    const textarea = document.querySelector('[data-editor-body]');
    if (!textarea) return;

    const form = textarea.closest('form');
    const statusEl = document.querySelector('[data-autosave-status]');
    const countEl = document.querySelector('[data-word-count]');
    const previewEl = document.querySelector('[data-md-preview]');
    const autosaveUrl = textarea.dataset.autosaveUrl || '';
    const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';

    // 文字数はサーバー側 mb_strlen(strip_tags()) に合わせる（プレーンテキストなので全文字数）
    const countWords = (text) => text.length;

    const renderPreview = () => {
        if (!previewEl) return;
        const value = textarea.value.trim();
        previewEl.innerHTML = value
            ? marked.parse(value)
            : '<p class="text-stone-400">ここに本文のプレビューが表示されます。</p>';
    };

    const updateCount = () => {
        if (countEl) countEl.textContent = countWords(textarea.value).toLocaleString();
    };

    // ---- 自動保存（入力停止から少し待ってから保存）----
    let timer = null;
    let lastSaved = textarea.value;

    const setStatus = (text, tone = 'muted') => {
        if (!statusEl) return;
        statusEl.textContent = text;
        statusEl.dataset.tone = tone;
    };

    const autosave = async () => {
        if (!autosaveUrl || textarea.value === lastSaved) return;
        const body = textarea.value;
        setStatus('保存中…', 'saving');
        try {
            const res = await fetch(autosaveUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body }),
            });
            if (!res.ok) throw new Error('save failed');
            const data = await res.json();
            lastSaved = body;
            const time = new Date().toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit' });
            setStatus(`下書きを保存しました（${time}）`, 'saved');
            if (countEl && typeof data.word_count === 'number') {
                countEl.textContent = data.word_count.toLocaleString();
            }
        } catch (e) {
            setStatus('保存に失敗しました。通信環境をご確認ください。', 'error');
        }
    };

    const scheduleAutosave = () => {
        if (!autosaveUrl) return;
        setStatus('未保存の変更があります…', 'dirty');
        clearTimeout(timer);
        timer = setTimeout(autosave, 1200);
    };

    textarea.addEventListener('input', () => {
        updateCount();
        renderPreview();
        scheduleAutosave();
    });

    // 未保存のまま離脱しようとしたら確認を出す（保存はデバウンスで実行済みのことが多い）
    window.addEventListener('beforeunload', (e) => {
        if (autosaveUrl && textarea.value !== lastSaved) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // 初期表示
    updateCount();
    renderPreview();
}
