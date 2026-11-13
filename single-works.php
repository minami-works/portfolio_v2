<?php get_header(); ?>

<?php while (have_posts()) : the_post();
  $id         = get_the_ID();
  $number     = mw2_work_number($id);
  $work_tags  = get_the_terms($id, 'work_tag');
  $work_tags  = (!$work_tags || is_wp_error($work_tags)) ? array() : $work_tags;
  $image      = mw2_field('image_pc');
  $github_url = mw2_field('github_url');
  $site_url   = mw2_field('site_url');
  $site_label = mw2_field('site_label');
  $overview   = mw2_field('overview');
  $note       = mw2_field('overview_note');
  $scope      = mw2_lines(mw2_field('scope'));
  $points     = mw2_lines(mw2_field('points'));

  // 画面キャプチャ（画像が入っている枠だけ使う）
  $pc_screens = array();
  $sp_screens = array();
  foreach (array(1, 2) as $n) {
    if ($img = mw2_field('screen_pc_' . $n)) $pc_screens[] = array('img' => $img, 'caption' => mw2_field('screen_pc_' . $n . '_caption'));
    if ($img = mw2_field('screen_sp_' . $n)) $sp_screens[] = array('img' => $img, 'caption' => mw2_field('screen_sp_' . $n . '_caption'));
  }

  // 前後の作品（最初と最後はループさせる）
  $ids   = mw2_work_ids();
  $total = count($ids);
  $index = array_search($id, $ids, true);
  $prev  = $total > 1 ? $ids[($index - 1 + $total) % $total] : null;
  $next  = $total > 1 ? $ids[($index + 1) % $total] : null;
?>
<main class="work-detail">
  <!-- ========== 見出し ========== -->
  <section class="work-hero">
    <div class="container">
      <a class="work-hero__back" href="<?php echo esc_url(home_url('/#works')); ?>">制作実績の一覧へ戻る</a>
      <h1 class="work-hero__title"><?php the_title(); ?></h1>
      <?php if (mw2_field('work_ja')) : ?>
        <p class="work-hero__subtitle"><?php echo esc_html(mw2_field('work_ja')); ?></p>
      <?php endif; ?>
      <?php if ($work_tags) : ?>
        <ul class="work-hero__tags">
          <?php foreach ($work_tags as $tag) : ?>
            <li class="pill-tag"><?php echo esc_html($tag->name); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php
        // 空欄の項目は表示しない
        $meta = array_filter(array(
          '担当'     => mw2_join(mw2_field('role')),
          '使用技術' => mw2_field('tech'),
          '制作期間' => mw2_field('period'),
        ));
      ?>
      <!-- 担当・技術・リンクは、画像より先（最初の画面内）に見せる -->
      <?php if ($meta) : ?>
        <dl class="work-meta work-meta--detail">
          <?php foreach ($meta as $key => $value) : ?>
            <div class="work-meta__item"><dt class="work-meta__key"><?php echo esc_html($key); ?></dt><dd class="work-meta__value"><?php echo esc_html($value); ?></dd></div>
          <?php endforeach; ?>
        </dl>
      <?php endif; ?>

      <?php if ($github_url || $site_url) : ?>
        <div class="work-hero__links">
          <?php if ($site_url) : ?>
            <a class="button button--primary button--small" href="<?php echo esc_url($site_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($site_label ? $site_label : 'サイトを見る'); ?></a>
          <?php endif; ?>
          <?php if ($github_url) : ?>
            <a class="button button--outline button--small" href="<?php echo esc_url($github_url); ?>" target="_blank" rel="noopener">GitHub</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($image) :
        // クリックで開く画像：「PC画面 1」があればそのページ全体、なければメイン画像
        $zoom_src = $pc_screens ? mw2_image_url($pc_screens[0]['img']) : mw2_image_url($image);
      ?>
        <!-- メイン画像はモックアップでも縦長キャプチャでもOK。16:10 の枠に上から合わせて表示 -->
        <figure class="work-hero__image">
          <button class="work-hero__zoom js-zoom" type="button" data-src="<?php echo esc_url($zoom_src); ?>" aria-label="<?php echo esc_attr($pc_screens ? 'PCでのページ全体を拡大して見る' : 'メイン画像を拡大して見る'); ?>">
            <img class="work-hero__img" src="<?php echo esc_url(mw2_image_url($image)); ?>" alt="<?php echo esc_attr(get_the_title() . ' のメイン画像'); ?>">
          </button>
          <figcaption class="work-hero__caption"><?php echo esc_html($pc_screens ? 'クリックでページ全体を表示' : 'クリックで拡大'); ?></figcaption>
        </figure>
      <?php endif; ?>
    </div>
  </section>

  <!-- ========== 概要・担当範囲 ========== -->
  <?php if ($overview || $scope) : ?>
    <section class="section section--gray">
      <div class="container work-overview">
        <div class="work-overview__body">
          <h2 class="section__title">概要</h2>
          <?php if ($overview) : ?><p class="work-overview__text"><?php echo nl2br(esc_html($overview)); ?></p><?php endif; ?>
          <?php if ($note) : ?><p class="work-overview__note"><?php echo nl2br(esc_html($note)); ?></p><?php endif; ?>
        </div>
        <?php if ($scope) : ?>
          <div class="scope">
            <h3 class="scope__title">担当範囲</h3>
            <ul class="scope__list">
              <?php foreach ($scope as $item) : ?>
                <li class="scope__item"><?php echo esc_html($item); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- ========== 画面 ========== -->
  <?php if ($pc_screens || $sp_screens) : ?>
    <section class="section">
      <div class="container">
        <div class="section__head section__head--split">
          <div>
            <h2 class="section__title">画面</h2>
            <p class="section__lead">PCとスマートフォンでの表示を、実際の画面で掲載しています。</p>
          </div>
          <p class="screens__hint">画像をクリックすると拡大できます</p>
        </div>

        <?php if ($pc_screens) : ?>
          <div class="screens">
            <?php foreach ($pc_screens as $screen) : ?>
              <figure class="screen-shot">
                <!-- 枠の中をスクロールして、ページ全体を見られるようにする -->
                <div class="screen-shot__frame">
                  <button class="screen-shot__button js-zoom" type="button" data-src="<?php echo esc_url(mw2_image_url($screen['img'])); ?>" aria-label="<?php echo esc_attr($screen['caption'] . ' を拡大'); ?>">
                    <img class="screen-shot__img" src="<?php echo esc_url(mw2_image_url($screen['img'])); ?>" alt="<?php echo esc_attr(get_the_title() . ' PC ' . $screen['caption']); ?>" loading="lazy">
                  </button>
                </div>
                <?php if ($screen['caption']) : ?>
                  <figcaption class="screen-shot__caption">PC：<?php echo esc_html($screen['caption']); ?></figcaption>
                <?php endif; ?>
              </figure>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($sp_screens) : ?>
          <div class="sp-screens">
            <h3 class="sp-screens__label">スマートフォン</h3>
            <div class="sp-screens__list">
              <?php foreach ($sp_screens as $screen) : ?>
                <figure class="phone-frame">
                  <button class="phone-frame__button js-zoom" type="button" data-src="<?php echo esc_url(mw2_image_url($screen['img'])); ?>" aria-label="<?php echo esc_attr($screen['caption'] . ' を拡大'); ?>">
                    <img class="phone-frame__img" src="<?php echo esc_url(mw2_image_url($screen['img'])); ?>" alt="<?php echo esc_attr(get_the_title() . ' SP ' . $screen['caption']); ?>" loading="lazy">
                  </button>
                  <figcaption class="phone-frame__caption"><?php echo esc_html($screen['caption']); ?></figcaption>
                </figure>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- 拡大表示（メイン画像・画面キャプチャのどれかがあるときだけ） -->
  <?php if ($image || $pc_screens || $sp_screens) : ?>
    <dialog class="lightbox js-lightbox" aria-label="拡大画像">
      <button class="lightbox__close js-lightbox-close" type="button" aria-label="閉じる">×</button>
      <img class="lightbox__img" src="" alt="">
    </dialog>
  <?php endif; ?>

  <!-- ========== 工夫した点 ========== -->
  <?php if ($points) : ?>
    <section class="section">
      <div class="container">
        <div class="section__head">
          <h2 class="section__title">工夫した点</h2>
        </div>
        <ul class="notes">
          <?php foreach ($points as $point) : ?>
            <li class="notes__item"><?php echo esc_html($point); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
  <?php endif; ?>

  <!-- ========== 前後の作品 ========== -->
  <nav class="section section--gray section--pager" aria-label="ほかの実績">
    <div class="container">
      <?php if ($prev && $next) : ?>
        <p class="pager__head">ほかの実績も見る（<?php echo esc_html($total . '件中 ' . (int) $number . '件目'); ?>）</p>
        <div class="pager">
          <a class="pager__card pager__card--prev" href="<?php echo esc_url(get_permalink($prev)); ?>">
            <span class="pager__dir">前の実績</span>
            <span class="pager__title"><?php echo esc_html(get_the_title($prev)); ?></span>
            <span class="pager__sub"><?php echo esc_html(mw2_field('work_ja', $prev)); ?></span>
          </a>
          <a class="pager__card pager__card--next" href="<?php echo esc_url(get_permalink($next)); ?>">
            <span class="pager__dir">次の実績</span>
            <span class="pager__title"><?php echo esc_html(get_the_title($next)); ?></span>
            <span class="pager__sub"><?php echo esc_html(mw2_field('work_ja', $next)); ?></span>
          </a>
        </div>
      <?php endif; ?>
      <p class="pager__back"><a class="text-link" href="<?php echo esc_url(home_url('/#works')); ?>">制作実績の一覧へ戻る</a></p>
    </div>
  </nav>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
