<?php get_header(); ?>

<main id="main">
  <!-- ========== hero ========== -->
  <?php
    // 最初の画面に代表作を1つ見せる：Worksの並び順で先頭の、メイン画像がある実績
    $featured     = null;
    $featured_img = null;
    $candidates   = get_posts(array(
      'post_type'      => 'works',
      'posts_per_page' => 5,
      'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
    ));
    foreach ($candidates as $candidate) {
      $img = mw2_field('image_pc', $candidate->ID);
      if ($img) {
        $featured     = $candidate;
        $featured_img = $img;
        break;
      }
    }
  ?>
  <section class="hero" aria-labelledby="hero-title">
    <div class="hero__inner container<?php echo $featured ? ' hero__inner--with-work' : ''; ?>">
      <div class="hero__text">
        <p class="hero__name">Minami Gouda<span class="hero__role">北海道のWebコーダー</span></p>
        <h1 class="hero__title" id="hero-title">確認を先に、<br>手戻りを少なく。</h1>
        <p class="hero__lead">LP制作を中心に、WordPressでの更新しやすいサイトづくりまでお手伝いしています。<br><span class="nowrap">気になる点は</span>作業に入る前に<span class="nowrap">まとめてお聞きし、</span><span class="nowrap">途中での作り直しを減らします。</span></p>
        <div class="hero__buttons">
          <a class="button button--primary" href="#contact">相談してみる</a>
          <a class="button button--outline" href="#works">制作実績を見る</a>
        </div>
        <p class="hero__status">ただいまご依頼を受け付けています</p>
      </div>

      <?php if ($featured) :
        $featured_title = get_the_title($featured);
        $featured_ja    = mw2_field('work_ja', $featured->ID);
      ?>
        <!-- 代表作：枠で囲まず、透過のモックアップ画像をそのまま置く。説明文は付けず、画像から詳細ページへ -->
        <a class="hero__work" href="<?php echo esc_url(get_permalink($featured)); ?>">
          <?php echo wp_get_attachment_image($featured_img['ID'], 'large', false, array(
            'class'         => 'hero__work-img',
            'alt'           => $featured_title . ($featured_ja ? '（' . $featured_ja . '）' : '') . 'のPC・スマートフォン表示',
            'loading'       => 'eager',
            'fetchpriority' => 'high',
            'sizes'         => '(min-width: 1024px) 460px, min(calc(100vw - 48px), 560px)',
          )); ?>
        </a>
      <?php endif; ?>
    </div>
  </section>

  <!-- ========== works ========== -->
  <section class="section section--gray" id="works" aria-labelledby="works-title">
    <div class="container">
      <div class="section__head">
        <h2 class="section__title" id="works-title">制作実績</h2>
        <p class="section__lead">担当範囲・使用技術・制作期間をまとめています。</p>
      </div>

      <?php
        $tags = get_terms(array('taxonomy' => 'work_tag', 'hide_empty' => true));
        if (!is_wp_error($tags) && $tags) :
      ?>
        <div class="works-filter js-works-filter" role="group" aria-label="実績の絞り込み">
          <button class="works-filter__button is-active" type="button" data-filter="all" aria-pressed="true">すべて</button>
          <?php foreach ($tags as $tag) : ?>
            <button class="works-filter__button" type="button" data-filter="<?php echo esc_attr($tag->slug); ?>" aria-pressed="false"><?php echo esc_html($tag->name); ?></button>
          <?php endforeach; ?>
        </div>
        <!-- 絞り込みの結果（件数）を画面読み上げで知らせる。見た目には表示しない -->
        <p class="visually-hidden js-works-status" role="status"></p>
      <?php endif; ?>

      <?php
        $works = new WP_Query(array(
          'post_type'      => 'works',
          'posts_per_page' => -1,
          'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
        ));
      ?>
      <?php if ($works->have_posts()) : ?>
        <ul class="works">
          <?php while ($works->have_posts()) : $works->the_post();
            $id         = get_the_ID();
            $work_tags  = get_the_terms($id, 'work_tag');
            $work_tags  = (!$work_tags || is_wp_error($work_tags)) ? array() : $work_tags;
            $slugs      = implode(' ', wp_list_pluck($work_tags, 'slug'));
            $image      = mw2_field('image_pc');
            $role       = mw2_join(mw2_field('role'));
            $tech       = mw2_field('tech');
            $period     = mw2_field('period');
            $github_url = mw2_field('github_url');
            $has_image  = $image || has_post_thumbnail(); // 画像がない実績は、空の枠を出さず本文だけのカードにする
          ?>
            <li class="work-card js-work-card<?php echo $has_image ? '' : ' work-card--no-image'; ?>" data-tags="<?php echo esc_attr($slugs); ?>">
              <div class="work-card__body">
                <h3 class="work-card__title"><?php the_title(); ?></h3>
                <?php if (mw2_field('work_ja')) : ?>
                  <p class="work-card__subtitle"><?php echo esc_html(mw2_field('work_ja')); ?></p>
                <?php endif; ?>

                <?php if ($work_tags) : ?>
                  <ul class="work-card__tags">
                    <?php foreach ($work_tags as $tag) : ?>
                      <li class="pill-tag"><?php echo esc_html($tag->name); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>

                <?php $meta = array_filter(array('担当' => $role, '使用技術' => $tech, '制作期間' => $period)); // 空欄は表示しない ?>
                <?php if ($meta) : ?>
                  <dl class="work-meta">
                    <?php foreach ($meta as $key => $value) : ?>
                      <div class="work-meta__item"><dt class="work-meta__key"><?php echo esc_html($key); ?></dt><dd class="work-meta__value"><?php echo esc_html($value); ?></dd></div>
                    <?php endforeach; ?>
                  </dl>
                <?php endif; ?>

                <div class="work-card__links">
                  <a class="text-link text-link--primary" href="<?php the_permalink(); ?>">詳しく見る</a>
                  <?php if ($github_url) : ?>
                    <a class="text-link" href="<?php echo esc_url($github_url); ?>" target="_blank" rel="noopener">GitHub</a>
                  <?php endif; ?>
                </div>
              </div>

              <?php if ($has_image) : ?>
              <a class="work-card__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php if ($image) :
                  // 画像の縦横サイズ（読み込み前に場所を確保して、表示のガタつきを防ぐ）
                  $img_w = $image['sizes']['medium_large-width'] ?? $image['width'];
                  $img_h = $image['sizes']['medium_large-height'] ?? $image['height'];
                ?>
                  <img class="work-card__img" src="<?php echo esc_url(mw2_image_url($image, 'medium_large')); ?>" alt="" width="<?php echo esc_attr($img_w); ?>" height="<?php echo esc_attr($img_h); ?>" loading="lazy">
                <?php elseif (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('large', array('class' => 'work-card__img', 'alt' => '', 'loading' => 'lazy')); ?>
                <?php endif; ?>
              </a>
              <?php endif; ?>
            </li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
        <p class="works__empty js-works-empty" hidden>この条件に合う実績はまだありません。「すべて」で一覧に戻れます。</p>
      <?php else : ?>
        <p class="works__empty">実績は準備中です。</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- ========== about ========== -->
  <section class="section" id="about" aria-labelledby="about-title">
    <div class="container">
      <div class="section__head">
        <h2 class="section__title" id="about-title">接客業と並行して、独学でコーダーに</h2>
      </div>
      <div class="about">
        <div class="about__body">
          <p class="about__text">北海道を拠点に、Webコーダーとして活動しています。ドラッグストアでの接客業を続けながら、2026年3月からHTML / CSS / SCSSを独学。現在はLP制作を中心に、デザインカンプからのコーディングやレスポンシブ対応に取り組んでいます。</p>
          <p class="about__text">もともとはデザインに興味があって学び始めましたが、実際に手を動かすうちに惹かれたのはコーディングの方でした。ある程度「正解」がある感覚と、書いたコードが目に見える形でサイトになっていく過程に楽しさを感じ、コーダーとして力をつけることに決めました。</p>
          <p class="about__text">接客業で培った「相手の状況を汲み取って伝える」コミュニケーション力は、ご依頼内容の確認やご連絡の場面でも活かしています。</p>
        </div>
        <!-- 右側のプロフィールカード（似顔絵＋基本情報） -->
        <div class="about__card">
          <div class="about__profile">
            <img class="about__avatar" src="<?php echo esc_url(get_template_directory_uri()); ?>/img/profile-icon.webp" alt="Minami Goudaの似顔絵" width="88" height="88" loading="lazy">
            <div>
              <p class="about__name">Minami Gouda</p>
              <p class="about__role">Webコーダー（北海道）</p>
            </div>
          </div>
          <dl class="about__facts">
            <div class="about__fact">
              <dt class="about__fact-key">拠点</dt>
              <dd class="about__fact-value">北海道（リモートで対応）</dd>
            </div>
            <div class="about__fact">
              <dt class="about__fact-key">得意なこと</dt>
              <dd class="about__fact-value">LP制作、WordPressでのサイト制作</dd>
            </div>
            <div class="about__fact">
              <dt class="about__fact-key">返信</dt>
              <dd class="about__fact-value">原則1営業日以内</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== skills ========== -->
  <section class="section section--gray" id="skills" aria-labelledby="skills-title">
    <div class="container">
      <div class="section__head">
        <h2 class="section__title" id="skills-title">対応できる技術</h2>
      </div>
      <table class="skills">
        <thead>
          <tr>
            <th class="skills__head" scope="col">技術</th>
            <th class="skills__head" scope="col">できること</th>
          </tr>
        </thead>
        <tbody>
          <tr class="skills__row">
            <th class="skills__name" scope="row">HTML / CSS</th>
            <td class="skills__note">セマンティックなマークアップ、Sass、BEM設計、レスポンシブ対応</td>
          </tr>
          <tr class="skills__row">
            <th class="skills__name" scope="row">JavaScript</th>
            <td class="skills__note">DOM操作、スクロール演出、フォーム制御、軽量なUI実装</td>
          </tr>
          <tr class="skills__row">
            <th class="skills__name" scope="row">WordPress</th>
            <td class="skills__note">オリジナルテーマ制作、カスタム投稿、ACFでの更新しやすい管理画面</td>
          </tr>
          <tr class="skills__row">
            <th class="skills__name" scope="row">レスポンシブ</th>
            <td class="skills__note">スマートフォン表示の崩れを防ぐ設計、主要ブラウザでの表示確認</td>
          </tr>
          <tr class="skills__row">
            <th class="skills__name" scope="row">ツール</th>
            <td class="skills__note">Figma / Git / GitHub / VS Code</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- ========== pricing ========== -->
  <section class="section" id="pricing" aria-labelledby="pricing-title">
    <div class="container">
      <div class="section__head">
        <h2 class="section__title" id="pricing-title">料金の目安</h2>
        <p class="section__lead">ページ数・仕様により変動します。</p>
      </div>

      <dl class="price-table">
        <div class="price-table__row">
          <dt class="price-table__name">LP制作</dt>
          <dd class="price-table__note">ボリューム・アニメーションの有無により変動</dd>
          <dd class="price-table__price">¥7,000〜¥25,000</dd>
        </div>
      </dl>
      <p class="pricing__sub">文字修正は1か所から、お気軽にご相談ください。</p>

      <div class="pricing__notes">
        <div class="note-box">
          <h3 class="note-box__title">修正対応について</h3>
          <p class="note-box__text">文言・色・余白の調整、画像差し替えなどの軽微な修正は「1回」としてまとめてご連絡ください。</p>
          <p class="note-box__text">レイアウトの大幅変更・ページ追加・<span class="nowrap">デザイン方向の変更は、</span>別途お見積もりいたします。</p>
        </div>
        <div class="note-box">
          <h3 class="note-box__title">JS実装の範囲について</h3>
          <p class="note-box__text">ハンバーガーメニュー・フェードイン・TOPへ戻るボタン・アコーディオンは標準で対応します。</p>
          <p class="note-box__text">タブ切り替え・モーダル・ローディングなどは、内容に応じてご相談ください。</p>
        </div>
      </div>

      <div class="cta">
        <p class="cta__text"><strong class="cta__lead">仕様が決まる前のご相談も受け付けています。</strong>ご希望の内容とご予算をお聞かせいただければ、対応範囲とお見積もりをお返しします。</p>
        <a class="button button--primary cta__button" href="#contact">相談してみる</a>
      </div>
    </div>
  </section>

  <!-- ========== contact ========== -->
  <section class="section section--gray" id="contact" aria-labelledby="contact-title">
    <div class="container contact">
      <div class="contact__info">
        <h2 class="section__title" id="contact-title">お問い合わせ</h2>
        <p class="contact__text">コーディングのご依頼・ご相談はこちらから。<br>「こんなことは頼めますか？」といった段階のご質問も歓迎です。</p>
        <p class="contact__text contact__text--note">原則1営業日以内にご返信します（お休み・営業時間外の場合は翌営業日中）。</p>
        <ul class="contact__links">
          <li><a class="text-link" href="https://github.com/minami-works" target="_blank" rel="noopener">GitHub（minami-works）</a></li>
          <li><a class="text-link" href="https://x.com/mnm_codes" target="_blank" rel="noopener">X（@mnm_codes）</a></li>
        </ul>
      </div>

      <!-- 送信先は現サイトと同じ Formspree -->
      <form class="contact-form js-contact-form" action="https://formspree.io/f/mkokgbld" method="POST">
        <div class="contact-form__row">
          <div class="contact-form__field">
            <label class="contact-form__label" for="cf-name">お名前</label>
            <input class="contact-form__input" id="cf-name" type="text" name="name" placeholder="山田 太郎" autocomplete="name" required>
          </div>
          <div class="contact-form__field">
            <label class="contact-form__label" for="cf-email">メールアドレス</label>
            <input class="contact-form__input" id="cf-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" spellcheck="false" required>
          </div>
        </div>
        <div class="contact-form__field">
          <label class="contact-form__label" for="cf-message">ご相談内容</label>
          <textarea class="contact-form__input contact-form__input--textarea" id="cf-message" name="message" rows="5" autocomplete="off" placeholder="ご依頼内容・ご予算・納期など、わかる範囲でお書きください" required></textarea>
        </div>
        <?php $privacy_url = get_privacy_policy_url() ? get_privacy_policy_url() : home_url('/privacy-policy/'); ?>
        <!-- 入力途中の内容が消えないよう、プライバシーポリシーは別タブで開く -->
        <p class="contact-form__note"><a class="text-link" href="<?php echo esc_url($privacy_url); ?>" target="_blank" rel="noopener">プライバシーポリシー</a>をご確認のうえ、同意いただける場合は送信してください。</p>
        <button class="button button--primary contact-form__submit" type="submit">送信する</button>
      </form>
    </div>
  </section>
</main>

<?php get_footer(); ?>
