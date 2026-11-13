<?php
/* 予備のテンプレート（トップは front-page.php、実績詳細は single-works.php が使われます） */
get_header(); ?>

<main class="section">
  <div class="container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article class="page-content">
        <h1 class="section__title"><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    <?php endwhile; else : ?>
      <div class="page-content">
        <h1 class="section__title">ページが見つかりません</h1>
        <p>URLが変わったか、ページが削除された可能性があります。</p>
        <p><a class="text-link text-link--primary" href="<?php echo esc_url(home_url('/')); ?>">トップページへ戻る</a></p>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
