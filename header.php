<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
  // トップページではページ内リンク、それ以外ではトップに戻ってから移動
  $home = is_front_page() ? '' : home_url('/');
  // アンカー（id） => 表示名
  $nav  = array(
    'about'   => 'わたしについて',
    'skills'  => 'できること',
    'works'   => '制作実績',
    'pricing' => '料金',
    'contact' => 'お問い合わせ',
  );
?>
<header class="header" id="top">
  <div class="header__inner">
    <a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">Minami Gouda</a>

    <nav class="header__nav" id="global-nav" aria-label="グローバルナビゲーション">
      <ul class="header__list">
        <?php foreach ($nav as $id => $label) : ?>
          <li><a class="header__link" href="<?php echo esc_url($home . '#' . $id); ?>"><?php echo esc_html($label); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="header__actions">
      <button class="theme-toggle js-theme-toggle" type="button" aria-label="ダークモードに切り替え">
        <svg class="theme-toggle__icon theme-toggle__icon--moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.98 6.98 0 0 0 10 7Zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12Z"/></svg>
        <svg class="theme-toggle__icon theme-toggle__icon--sun" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM11 1h2v3h-2V1Zm0 19h2v3h-2v-3ZM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93ZM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121Zm2.121-14.85 1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121ZM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121ZM23 11v2h-3v-2h3ZM4 11v2H1v-2h3Z"/></svg>
      </button>
      <button class="hamburger js-hamburger" type="button" aria-controls="global-nav" aria-expanded="false" aria-label="メニューを開く">
        <span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
      </button>
    </div>
  </div>
</header>
