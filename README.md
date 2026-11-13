# minamiworks v2

Webコーダー Minami Gouda のポートフォリオサイト用 WordPress オリジナルテーマです。
「相談しやすさ」を軸にした、ナチュラルなデザインにしています。

## Localでの使い方
1. Localで新しいサイトを作る
2. このフォルダを `wp-content/themes/minamiworks-v2/` に置き、「外観 > テーマ」で有効化
3. プラグイン **Advanced Custom Fields（無料版）** を入れて有効化
4. 「設定 > 表示設定」→ ホームページの表示を「固定ページ」にし、空の固定ページ（例：トップ）を指定
5. 「設定 > パーマリンク」→「投稿名」を選んで保存（/works/〇〇/ が404になる場合もここで保存し直す）
6. 「設定 > 一般」でサイトのタイトル、「外観 > カスタマイズ > サイト基本情報」でサイトアイコンを設定

## Worksの登録
- 「Works > 実績タグ」で絞り込み用タグを作る（例：LP制作 / WordPress）
- 「Works > 作品を追加」で入力。タブが「基本情報 / 詳細ページ / 画面キャプチャ」に分かれています
- 並び順は右側「ページ属性 > 順序」（小さい順。同じなら投稿日順）
- 担当範囲・工夫した点は「1行に1項目」
- 空欄の項目・画像のない枠は表示されません

### 画像の入れ方
| 欄 | 入れる画像 |
| --- | --- |
| メイン画像（PC） | PCとスマホを並べたモックアップ（2400×1500 / 16:10）。一覧のサムネイルにも使われる |
| PC画面 1 | PCのページ全体のスクリーンショット（幅1440）。メイン画像をクリックするとこれが開く |
| SP画面 1・2 | スマホのページ全体／メニューを開いた状態など |

- 縦長のキャプチャでも勝手に縮小されないよう、`functions.php` で WordPress の自動縮小（2560px超）を止めています
- 画像を差し替えるときはファイル名を変えると、ブラウザのキャッシュで古い画像が出る問題を避けられます

## SCSS
- `scss/style.scss` → `css/style.css` にコンパイル
- Live Sass Compiler の出力先を `/css` に設定（`liveSassCompile.settings.formats` の `savePath`）
- 色はダークモード対応のため `scss/_variables.scss` の CSSカスタムプロパティ（`--color-〇〇`）で管理
- 行間は **8pxの倍数** でそろえる。文字サイズと行間は `@include text(14px, 24px);` のようにセットで書く（8の倍数でないとコンパイル時にエラーで知らせる）
- 上下の余白（margin / padding）も 8・16・24・32… の倍数で書く
- フォントは Zen Maru Gothic の1種類のみ

## SEO・OGP
- `functions.php` の `mw2_ogp()` で meta description・OGP・Xカードを出力しています
- OGP画像は `img/ogp.png`（1200×630）
- SEOプラグインを入れる場合は、タグが二重になるので `add_action('wp_head', 'mw2_ogp', 5);` を消してください

## ファイル構成
| ファイル | 役割 |
| --- | --- |
| front-page.php | トップ（hero / about / skills / works / pricing / contact） |
| single-works.php | 実績の詳細ページ |
| header.php / footer.php | 共通ヘッダー・フッター |
| index.php | 予備テンプレート・404 |
| functions.php | 読み込み・カスタム投稿works・実績タグ・ヘルパー関数・OGP |
| inc/acf-fields.php | Works の ACF フィールド定義（コード管理） |
| js/main.js | ハンバーガー / ダークモード / 絞り込み / 画像拡大 |
| img/ | プロフィールの似顔絵・OGP画像 |

※ 作品のスクリーンショットやモックアップは WordPress のメディアで管理するため、リポジトリには含めていません（`.gitignore`）。
