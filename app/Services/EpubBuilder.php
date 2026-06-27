<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * 書籍から EPUB 3 ファイルを生成する。
 * 章を並び順で結合し、目次（nav）・表紙・スタイルを含む最小構成の EPUB を作る。
 */
class EpubBuilder
{
    public function __construct(private MarkdownRenderer $markdown)
    {
    }

    /**
     * EPUB を一時ファイルに書き出し、そのパスを返す。
     * 呼び出し側はダウンロード後に削除すること（response()->download(..., deleteFileAfterSend) 推奨）。
     */
    public function build(Book $book): string
    {
        $book->loadMissing('chapters');

        $path = tempnam(sys_get_temp_dir(), 'epub');
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::OVERWRITE);

        // mimetype は最初・非圧縮で格納する（EPUB 仕様）
        $zip->addFromString('mimetype', 'application/epub+zip');
        $zip->setCompressionName('mimetype', ZipArchive::CM_STORE);

        $zip->addFromString('META-INF/container.xml', $this->containerXml());
        $zip->addFromString('OEBPS/css/style.css', $this->styleCss());

        $hasCover = false;
        $coverImageName = null;
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            $ext = pathinfo($book->cover_path, PATHINFO_EXTENSION) ?: 'jpg';
            $coverImageName = "images/cover.{$ext}";
            $zip->addFromString("OEBPS/{$coverImageName}", Storage::disk('public')->get($book->cover_path));
            $zip->addFromString('OEBPS/text/cover.xhtml', $this->coverXhtml($coverImageName));
            $hasCover = true;
        }

        // タイトルページ
        $zip->addFromString('OEBPS/text/title.xhtml', $this->titleXhtml($book));

        // 各章
        $chapters = $book->chapters->values();
        foreach ($chapters as $i => $chapter) {
            $zip->addFromString(
                'OEBPS/text/chap-'.($i + 1).'.xhtml',
                $this->chapterXhtml($chapter->title, (string) $chapter->body)
            );
        }

        $zip->addFromString('OEBPS/nav.xhtml', $this->navXhtml($book, $chapters));
        $zip->addFromString('OEBPS/package.opf', $this->packageOpf($book, $chapters, $hasCover, $coverImageName));

        $zip->close();

        return $path;
    }

    /** ダウンロード用のファイル名（日本語タイトルでも安全に） */
    public function filename(Book $book): string
    {
        $base = trim(preg_replace('/[\/\\\\:*?"<>|]+/', '', $book->title));

        return ($base !== '' ? $base : 'book-'.$book->id).'.epub';
    }

    private function containerXml(): string
    {
        return <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">
          <rootfiles>
            <rootfile full-path="OEBPS/package.opf" media-type="application/oebps-package+xml"/>
          </rootfiles>
        </container>
        XML;
    }

    private function styleCss(): string
    {
        return <<<'CSS'
        body { font-family: "Hiragino Sans", "Noto Sans JP", sans-serif; line-height: 1.8; margin: 5%; }
        h1 { font-size: 1.6em; margin: 1em 0 0.6em; }
        h2 { font-size: 1.3em; margin: 1.2em 0 0.5em; }
        h3 { font-size: 1.1em; }
        p { margin: 0 0 1em; }
        blockquote { border-left: 3px solid #ccc; margin: 1em 0; padding-left: 1em; color: #555; }
        .cover { text-align: center; margin: 0; padding: 0; }
        .cover img { max-width: 100%; height: auto; }
        .title-page { text-align: center; margin-top: 30%; }
        .title-page .subtitle { color: #555; font-size: 1.1em; margin-top: 0.5em; }
        .title-page .author { margin-top: 2em; }
        CSS;
    }

    private function coverXhtml(string $coverImageName): string
    {
        return $this->wrapXhtml('表紙', '<div class="cover"><img src="../'.$this->e($coverImageName).'" alt="表紙"/></div>');
    }

    private function titleXhtml(Book $book): string
    {
        $body = '<div class="title-page">';
        $body .= '<h1>'.$this->e($book->title).'</h1>';
        if ($book->subtitle) {
            $body .= '<p class="subtitle">'.$this->e($book->subtitle).'</p>';
        }
        if ($book->author_name) {
            $body .= '<p class="author">'.$this->e($book->author_name).'</p>';
        }
        $body .= '</div>';

        return $this->wrapXhtml($book->title, $body);
    }

    private function chapterXhtml(string $title, string $markdown): string
    {
        $html = $this->markdown->toHtml($markdown);

        return $this->wrapXhtml($title, '<h1>'.$this->e($title).'</h1>'."\n".$html);
    }

    /** EPUB3 のナビゲーション（目次） */
    private function navXhtml(Book $book, $chapters): string
    {
        $items = '';
        foreach ($chapters as $i => $chapter) {
            $items .= '<li><a href="text/chap-'.($i + 1).'.xhtml">'.$this->e($chapter->title).'</a></li>'."\n";
        }

        $nav = '<nav epub:type="toc" id="toc"><h1>目次</h1><ol>'."\n".$items.'</ol></nav>';

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" lang="ja">'."\n"
            .'<head><meta charset="utf-8"/><title>目次</title></head>'."\n"
            .'<body>'.$nav.'</body></html>';
    }

    private function packageOpf(Book $book, $chapters, bool $hasCover, ?string $coverImageName): string
    {
        $uid = 'urn:kindle-techo:book:'.$book->id;
        $modified = now()->utc()->format('Y-m-d\TH:i:s\Z');

        $manifest = '<item id="nav" href="nav.xhtml" media-type="application/xhtml+xml" properties="nav"/>'."\n";
        $manifest .= '<item id="css" href="css/style.css" media-type="text/css"/>'."\n";
        $manifest .= '<item id="title" href="text/title.xhtml" media-type="application/xhtml+xml"/>'."\n";

        $spine = '';
        if ($hasCover) {
            $coverMime = str_ends_with($coverImageName, '.png') ? 'image/png' : 'image/jpeg';
            $manifest .= '<item id="cover-image" href="'.$this->e($coverImageName).'" media-type="'.$coverMime.'" properties="cover-image"/>'."\n";
            $manifest .= '<item id="cover" href="text/cover.xhtml" media-type="application/xhtml+xml"/>'."\n";
            $spine .= '<itemref idref="cover"/>'."\n";
        }
        $spine .= '<itemref idref="title"/>'."\n";

        foreach ($chapters as $i => $chapter) {
            $n = $i + 1;
            $manifest .= '<item id="chap-'.$n.'" href="text/chap-'.$n.'.xhtml" media-type="application/xhtml+xml"/>'."\n";
            $spine .= '<itemref idref="chap-'.$n.'"/>'."\n";
        }

        $author = $book->author_name ? '<dc:creator>'.$this->e($book->author_name).'</dc:creator>' : '';

        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<package xmlns="http://www.idpf.org/2007/opf" version="3.0" unique-identifier="bookid" xml:lang="ja">'."\n"
            .'<metadata xmlns:dc="http://purl.org/dc/elements/1.1/">'."\n"
            .'<dc:identifier id="bookid">'.$this->e($uid).'</dc:identifier>'."\n"
            .'<dc:title>'.$this->e($book->title).'</dc:title>'."\n"
            .'<dc:language>ja</dc:language>'."\n"
            .$author."\n"
            .'<meta property="dcterms:modified">'.$modified.'</meta>'."\n"
            .($hasCover ? '<meta name="cover" content="cover-image"/>'."\n" : '')
            .'</metadata>'."\n"
            .'<manifest>'."\n".$manifest.'</manifest>'."\n"
            .'<spine>'."\n".$spine.'</spine>'."\n"
            .'</package>';
    }

    /** XHTML の共通ラッパー */
    private function wrapXhtml(string $title, string $bodyHtml): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<html xmlns="http://www.w3.org/1999/xhtml" lang="ja">'."\n"
            .'<head><meta charset="utf-8"/><title>'.$this->e($title).'</title>'
            .'<link rel="stylesheet" type="text/css" href="../css/style.css"/></head>'."\n"
            .'<body>'.$bodyHtml.'</body></html>';
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
