<?php
/**
 * minamiworks v2 functions
 */

/* ---------- テーマの基本設定 ---------- */
function mw2_setup() {
  add_theme_support('title-tag');        // <title> をWPが出力
  add_theme_support('post-thumbnails');  // アイキャッチ（Worksの一覧サムネに使用）
  add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'mw2_setup');

/* ---------- CSS / JS / フォント読み込み ---------- */
function mw2_enqueue() {
  $dir = get_template_directory();
  $uri = get_template_directory_uri();

  wp_enqueue_style(
    'mw2-fonts',
    'https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700&display=swap',
    array(),
    null
  );
  // filemtime = ファイル更新日時をバージョンにする（キャッシュ対策）
  wp_enqueue_style('mw2-style', $uri . '/css/style.css', array('mw2-fonts'), filemtime($dir . '/css/style.css'));
  wp_enqueue_script('mw2-main', $uri . '/js/main.js', array(), filemtime($dir . '/js/main.js'), true);
}
add_action('wp_enqueue_scripts', 'mw2_enqueue');

/* Google Fonts の preconnect */
function mw2_resource_hints($urls, $relation_type) {
  if ('preconnect' === $relation_type) {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin');
  }
  return $urls;
}
add_filter('wp_resource_hints', 'mw2_resource_hints', 10, 2);

/* ダークモード：表示のチラつきを防ぐため、CSSより先に<head>でクラスを付ける
   初期表示はライトモード。ボタンでダークを選んだ人だけ、次回もダークで表示する */
function mw2_theme_mode_script() {
  ?>
  <script>
    (function () {
      try {
        if (localStorage.getItem('mw2-theme') === 'dark') document.documentElement.classList.add('is-dark');
      } catch (e) {}
    })();
  </script>
  <?php
}
add_action('wp_head', 'mw2_theme_mode_script', 1);

/* ---------- meta description と OGP（SNSでシェアされたときの表示） ----------
   ※ SEOプラグイン（Yoast・SEO SIMPLE PACK など）を入れる場合は、
     同じタグが二重に出るので、この関数の add_action を消してください */
function mw2_ogp() {
  $default_description = '北海道のWebコーダー Minami Gouda のポートフォリオ。Figmaカンプからのコーディングとレスポンシブ対応を中心に、LP制作をお手伝いしています。';

  if (is_singular('works')) {
    // 実績の詳細ページ：概要の冒頭を説明文にする
    $overview    = mw2_field('overview');
    $description = $overview ? wp_trim_words(wp_strip_all_tags($overview), 80, '…') : $default_description;
    $url         = get_permalink();
    $type        = 'article';
  } else {
    $description = $default_description;
    $url         = is_front_page() ? home_url('/') : get_permalink();
    $type        = is_front_page() ? 'website' : 'article';
  }
  $title = wp_get_document_title();
  $image = get_template_directory_uri() . '/img/ogp.png'; // 1200×630
  ?>
  <meta name="description" content="<?php echo esc_attr($description); ?>">
  <meta property="og:type" content="<?php echo esc_attr($type); ?>">
  <meta property="og:title" content="<?php echo esc_attr($title); ?>">
  <meta property="og:description" content="<?php echo esc_attr($description); ?>">
  <meta property="og:url" content="<?php echo esc_url($url); ?>">
  <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
  <meta property="og:image" content="<?php echo esc_url($image); ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="ja_JP">
  <meta name="twitter:card" content="summary_large_image">
  <?php
}
add_action('wp_head', 'mw2_ogp', 5);

/* ---------- カスタム投稿タイプ：works ---------- */
function mw2_register_works() {
  register_post_type('works', array(
    'labels' => array(
      'name'          => 'Works',
      'singular_name' => 'Work',
      'add_new_item'  => '作品を追加',
      'edit_item'     => '作品を編集',
    ),
    'public'       => true,
    'has_archive'  => false,
    'menu_icon'    => 'dashicons-portfolio',
    'supports'     => array('title', 'thumbnail', 'page-attributes'), // page-attributes = 「順序」で並び替え
    'rewrite'      => array('slug' => 'works'),
    'show_in_rest' => true,
  ));

  /* 絞り込み用タグ（LP制作 / WordPress / コーディングのみ / レスポンシブ対応 など） */
  register_taxonomy('work_tag', 'works', array(
    'labels' => array(
      'name'          => '実績タグ',
      'singular_name' => '実績タグ',
      'add_new_item'  => 'タグを追加',
    ),
    'hierarchical'      => false,
    'public'            => false,
    'show_ui'           => true,
    'show_admin_column' => true,
    'show_in_rest'      => true,
  ));
}
add_action('init', 'mw2_register_works');

/* テーマ有効化時にパーマリンクを更新（/works/〇〇/ が404にならないように） */
function mw2_flush_rewrite() {
  mw2_register_works();
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'mw2_flush_rewrite');

/* ---------- ヘルパー関数 ---------- */

/* 「1行1項目」のテキストエリアを配列にする */
function mw2_lines($text) {
  if (!$text) return array();
  return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
}

/* role（チェックボックス＝配列 / テキスト どちらでも）を「 / 」区切りの文字列にする */
function mw2_join($value) {
  if (is_array($value)) return implode(' / ', array_filter($value));
  return (string) $value;
}

/* 2桁の通し番号（01, 02 ...）。順序（menu_order）→投稿日の順で数える */
function mw2_work_ids() {
  static $ids = null;
  if (null === $ids) {
    $ids = get_posts(array(
      'post_type'      => 'works',
      'posts_per_page' => -1,
      'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
      'fields'         => 'ids',
    ));
  }
  return $ids;
}
function mw2_work_number($post_id) {
  $index = array_search($post_id, mw2_work_ids(), true);
  return false === $index ? '' : sprintf('%02d', $index + 1);
}

/* ACFの画像（配列）からURLを取り出す
   'full' のときは、WordPressが自動で縮小した「-scaled」版ではなく元画像を使う */
function mw2_image_url($image, $size = 'full') {
  if (!$image) return '';
  if ('full' === $size) {
    $original = wp_get_original_image_url($image['ID']);
    return $original ? $original : $image['url'];
  }
  return $image['sizes'][$size] ?? $image['url'];
}

/* 縦長のページ全体キャプチャが、アップロード時に勝手に縮小されないようにする
   （初期設定では長辺2560pxを超えると縮小され、スマホのキャプチャが幅195pxなどになってしまう） */
add_filter('big_image_size_threshold', '__return_false');

/* ACF未導入でも画面が真っ白にならないように */
function mw2_field($name, $post_id = false) {
  return function_exists('get_field') ? get_field($name, $post_id) : '';
}

/* ACFフィールド定義 */
require_once get_template_directory() . '/inc/acf-fields.php';
