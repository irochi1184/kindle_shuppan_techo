import Alpine from 'alpinejs';

import initChapterEditor from './chapter-editor';
import initChapterSort from './chapter-sort';
import initCopyButtons from './copy';

window.Alpine = Alpine;
Alpine.start();

// 各画面の拡張（対象 DOM が無ければ何もしない）
initChapterEditor();
initChapterSort();
initCopyButtons();
