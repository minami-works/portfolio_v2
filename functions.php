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
  <!-- スマホのブラウザ上部（アドレスバー周り）の色を、ページの背景色に合わせる。ダークモードの時は下のJSで切り替える -->
  <meta name="theme-color" content="#f7f7f3">
  <script>
    (function () {
      try {
        if (localStorage.getItem('mw2-theme') === 'dark') {
          document.documentElement.classList.add('is-dark');
          document.querySelector('meta[name="theme-color"]').setAttribute('content', '#1f2420');
        }
      } catch (e) {}
    })();
  </script>
  <?php
}
add_action('wp_head', 'mw2_theme_mode_script', 1);

/* ---------- Google アナリティクス（GA4） ----------
   前のテーマの header.php に直接書いていたタグを、テーマを替えても消えないよう functions.php から出す。
   次の場合は計測しない（数字に混ざらないように）：
   ・ローカル環境（Local では wp-config.php で WP_ENVIRONMENT_TYPE が 'local'。本番は 'production'）
   ・ログイン中（自分で確認のために開いたアクセス） */
function mw2_google_analytics() {
  if ('production' !== wp_get_environment_type() || is_user_logged_in()) return;
  $ga_id = 'G-3H94STZJ3V';
  ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js($ga_id); ?>');
  </script>
  <?php
}
add_action('wp_head', 'mw2_google_analytics', 1);

/* ---------- meta description と OGP（SNSでシェアされたときの表示） ----------
   ※ SEOプラグイン（Yoast・SEO SIMPLE PACK など）を入れる場合は、
     同じタグが二重に出るので、この関数の add_action を消してください */
function mw2_ogp() {
  $default_description = '北海道のWebコーダー Minami Gouda のポートフォリオ。LP制作を中心に、WordPressでの更新しやすいサイトづくりまでお手伝いしています。';

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
  // canonical：WordPressは投稿・固定ページにしか自動で出さないので、トップページだけここで出す
  if (is_front_page() && !is_singular()) : ?>
  <link rel="canonical" href="<?php echo esc_url(home_url('/')); ?>">
  <?php endif;

  // 構造化データ（JSON-LD）：トップページで「誰のどんなサイトか」を検索エンジンに伝える
  if (is_front_page()) {
    $json_ld = array(
      '@context' => 'https://schema.org',
      '@graph'   => array(
        array(
          '@type'       => 'WebSite',
          'name'        => get_bloginfo('name'),
          'url'         => home_url('/'),
          'inLanguage'  => 'ja',
          'description' => $default_description,
        ),
        array(
          '@type'    => 'Person',
          'name'     => 'Minami Gouda',
          'jobTitle' => 'Webコーダー',
          'url'      => home_url('/'),
          'sameAs'   => array('https://github.com/minami-works', 'https://x.com/mnm_codes'),
        ),
      ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
  }
}
add_action('wp_head', 'mw2_ogp', 5);

/* ---------- ユーザー名（ログインIDのヒント）を外に出さない ----------
   WordPressの初期設定では、次の2か所から誰でもユーザー名を見られてしまう
   ① REST API（/wp-json/wp/v2/users）　② XMLサイトマップ（wp-sitemap-users-1.xml）
   ※ ログイン中は管理画面（ブロックエディタ）が①を使うので、ログインしていない時だけ塞ぐ */
function mw2_hide_users_endpoint($endpoints) {
  if (!is_user_logged_in()) {
    unset($endpoints['/wp/v2/users']);
    unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  }
  return $endpoints;
}
add_filter('rest_endpoints', 'mw2_hide_users_endpoint');

function mw2_remove_users_sitemap($provider, $name) {
  return ('users' === $name) ? false : $provider;
}
add_filter('wp_sitemaps_add_provider', 'mw2_remove_users_sitemap', 10, 2);

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

/*
 * 担当・使用技術などを「 / 」区切りで出力する（エスケープ済みのHTMLを返す）
 * - チェックボックス（配列）でも「A / B」形式のテキストでもOK
 * - 1項目ずつ <span> で囲み、改行は項目の切れ目だけで起こるようにする
 *   （行頭に「/」が来たり、項目の途中で折り返したりしない）
 */
function mw2_meta_html($value) {
  $parts = is_array($value) ? $value : explode('/', (string) $value);
  $parts = array_values(array_filter(array_map('trim', $parts), 'strlen'));
  $last  = count($parts) - 1;
  $html  = array();
  foreach ($parts as $i => $part) {
    $html[] = '<span class="work-meta__part">' . esc_html($part) . ($i < $last ? ' /' : '') . '</span>';
  }
  return implode(' ', $html);
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

/*
 * 担当（role）を、選択肢の並び（＝制作の流れ）の順にそろえ、表示名にして返す
 * - ACFは「保存した時の順番」で値を持っているので、並び替える前に保存した投稿もここで並び直す
 * - 値（例：JS実装）は表示名（例：JavaScript実装）に置き換える
 * - 選択肢にない値（手入力したもの）はそのままの文字で最後に回す
 */
function mw2_role($post_id = false) {
  $roles = mw2_field('role', $post_id);
  if (!is_array($roles) || !function_exists('acf_get_field')) return $roles;
  $field   = acf_get_field('field_mw2_role');
  $choices = $field ? $field['choices'] : array();
  $order   = array_keys($choices);
  usort($roles, function ($a, $b) use ($order) {
    $ia = array_search($a, $order, true);
    $ib = array_search($b, $order, true);
    return ($ia === false ? PHP_INT_MAX : $ia) <=> ($ib === false ? PHP_INT_MAX : $ib);
  });
  return array_map(function ($value) use ($choices) {
    return isset($choices[$value]) ? $choices[$value] : $value;
  }, $roles);
}

/* ACFフィールド定義 */
require_once get_template_directory() . '/inc/acf-fields.php';
