<?php
/*
 * 料金ページ（スラッグが pricing の固定ページで自動的に使われる）
 * 金額・条件は「料金表_Local反映用_1127.md」（2026-11-27決定）の内容だけを使う。表示価格はすべて税込
 */
get_header(); ?>

<main class="pricing-page" id="main">
  <!-- ========== 見出し ========== -->
  <section class="page-hero" aria-labelledby="page-title">
    <div class="container">
      <?php while (have_posts()) : the_post(); ?>
        <h1 class="page-hero__title" id="page-title"><?php the_title(); ?></h1>
      <?php endwhile; ?>
      <p class="page-hero__lead">ご依頼内容ごとの料金の目安です。ページ数・機能によりお見積もりいたします。</p>
      <p class="page-hero__tax">表示価格はすべて税込です。</p>

      <!-- ページ内の目次（長いページなので、見たい料金へすぐ移動できるように） -->
      <nav class="page-index" aria-label="このページの目次">
        <ul class="page-index__list">
          <li><a class="page-index__link" href="#plan-lp">LP制作</a></li>
          <li><a class="page-index__link" href="#plan-wordpress">WordPressサイト制作</a></li>
          <li><a class="page-index__link" href="#plan-launch">公開作業の代行</a></li>
          <li><a class="page-index__link" href="#plan-maintenance">保守</a></li>
          <li><a class="page-index__link" href="#flow">制作の流れ</a></li>
        </ul>
      </nav>
    </div>
  </section>

  <!-- ========== 料金表 ========== -->
  <div class="section section--gray">
    <div class="container price-plans">

      <!-- ---------- LP制作 ---------- -->
      <section class="price-plan" id="plan-lp" aria-labelledby="plan-lp-title">
        <h2 class="price-plan__title" id="plan-lp-title">LP制作</h2>
        <dl class="price-table">
          <div class="price-table__row">
            <dt class="price-table__name">デザインデータ<wbr>支給</dt>
            <dd class="price-table__note">セクション4つ程度（ヘッダー・フッターを除く）・スマホ対応込み</dd>
            <dd class="price-table__price">11,000円〜</dd>
          </div>
          <div class="price-table__row">
            <dt class="price-table__name">デザイン込み</dt>
            <dd class="price-table__note">セクション4つ程度（ヘッダー・フッターを除く）・スマホ対応込み</dd>
            <dd class="price-table__price">33,000円〜</dd>
          </div>
          <div class="price-table__row">
            <dt class="price-table__name">それ以上の規模</dt>
            <dd class="price-table__price price-table__price--ask">お見積もり</dd>
          </div>
        </dl>

        <div class="price-plan__notes">
          <div class="price-notes">
            <h3 class="price-notes__title">デザインデータ支給の場合</h3>
            <ul class="price-notes__list">
              <li class="price-notes__item">文章・画像はご支給いただきます</li>
              <li class="price-notes__item">アニメーションはなし、または簡単なものまでです</li>
              <li class="price-notes__item">スマホ用のデザインがない場合は、PCのデザインをもとにこちらで調整いたします</li>
            </ul>
          </div>
          <div class="price-notes">
            <h3 class="price-notes__title">デザイン込みの場合</h3>
            <ul class="price-notes__list">
              <li class="price-notes__item">デザインの修正は、デザインの完成後2回まで無料です。3回目以降は、内容に応じてお見積もりいたします</li>
            </ul>
          </div>
          <!-- 「アニメーションは簡単なものまで」の中身。以前トップページにあった「JS実装の範囲について」を移したもの -->
          <div class="price-notes">
            <h3 class="price-notes__title">JavaScriptでの動きの範囲（どちらのプランも共通）</h3>
            <ul class="price-notes__list">
              <li class="price-notes__item">ハンバーガーメニュー・フェードイン・TOPへ戻るボタン・アコーディオンは標準で対応します</li>
              <li class="price-notes__item">タブ切り替え・モーダル・ローディングなどは、内容に応じてご相談ください</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ---------- WordPressサイト制作 ---------- -->
      <section class="price-plan" id="plan-wordpress" aria-labelledby="plan-wordpress-title">
        <h2 class="price-plan__title" id="plan-wordpress-title">WordPressサイト制作</h2>
        <dl class="price-table">
          <div class="price-table__row">
            <dt class="price-table__name">デザイン込み</dt>
            <dd class="price-table__note">10ページ程度・オリジナルテーマ</dd>
            <dd class="price-table__price">165,000円〜</dd>
          </div>
          <div class="price-table__row">
            <dt class="price-table__name">デザインデータ<wbr>支給</dt>
            <dd class="price-table__note">10ページ程度・オリジナルテーマ</dd>
            <dd class="price-table__price">132,000円〜</dd>
          </div>
        </dl>

        <div class="price-plan__notes">
          <ul class="price-notes__list">
            <li class="price-notes__item">ページ数・機能によりお見積もりいたします</li>
            <li class="price-notes__item">管理画面から更新できるサイトをお作りします</li>
            <li class="price-notes__item">構成やデザインの方向性は、制作前のヒアリングとワイヤーフレーム（配置の設計図）ですり合わせます</li>
            <li class="price-notes__item">デザインの修正は、デザインの完成後2回まで無料です。3回目以降は、内容に応じてお見積もりいたします</li>
            <li class="price-notes__item">修正1回とは、ご指摘をまとめてお送りいただき、それに対して修正版をご提出するまでを1回とします</li>
          </ul>
        </div>
      </section>

      <!-- ---------- 公開作業の代行 ---------- -->
      <section class="price-plan" id="plan-launch" aria-labelledby="plan-launch-title">
        <h2 class="price-plan__title" id="plan-launch-title">公開作業の代行</h2>
        <dl class="price-table">
          <!-- プラン名がない1行だけの表：内容を見出し（dt）にして、料金と組み合わせる -->
          <div class="price-table__row price-table__row--single">
            <dt class="price-table__summary">サーバー・ドメインの設定、WordPressのインストール、SSL（https化）の設定など</dt>
            <dd class="price-table__price">11,000円〜</dd>
          </div>
        </dl>

        <div class="price-plan__notes">
          <ul class="price-notes__list">
            <li class="price-notes__item">サーバー・ドメイン代は実費です（お客様名義でご契約いただき、直接お支払いいただきます）</li>
          </ul>
        </div>
      </section>

      <!-- ---------- 保守 ---------- -->
      <section class="price-plan" id="plan-maintenance" aria-labelledby="plan-maintenance-title">
        <h2 class="price-plan__title" id="plan-maintenance-title">保守</h2>
        <dl class="price-table">
          <div class="price-table__row price-table__row--single">
            <dt class="price-table__summary">WordPress本体・プラグインの更新、バックアップ、軽微な修正</dt>
            <dd class="price-table__price">月11,000円</dd>
          </div>
        </dl>

        <div class="price-plan__notes">
          <ul class="price-notes__list">
            <li class="price-notes__item">バックアップ：週1回（自動）＋更新作業の前</li>
            <li class="price-notes__item">
              軽微な修正：月2回まで・1回のご依頼につき3か所まで（使わなかった回数の繰り越しはありません）
              <dl class="price-notes__detail">
                <div class="price-notes__detail-row">
                  <dt class="price-notes__detail-key">対象の例</dt>
                  <dd class="price-notes__detail-value">文章の追加・差し替え、誤字の修正、画像の差し替え、リンク先の変更、余白・文字の大きさ・色の小さな調整</dd>
                </div>
                <div class="price-notes__detail-row">
                  <dt class="price-notes__detail-key">対象外（別途お見積もり）</dt>
                  <dd class="price-notes__detail-value">新しいページの追加、レイアウト・デザインの大きな変更、新しい機能の追加</dd>
                </div>
              </dl>
            </li>
            <li class="price-notes__item">サイトが表示されないなどの緊急時は、確認しだい優先して対応いたします</li>
          </ul>
        </div>
      </section>

      <!-- ---------- 対応について（全プラン共通） ----------
           料金のカードと見分けられるよう、カードにはせず線で区切る -->
      <section class="support" aria-labelledby="support-title">
        <h2 class="support__title" id="support-title">対応について<span class="support__sub">全プラン共通</span></h2>
        <dl class="support__list">
          <div class="support__item">
            <dt class="support__key">営業日</dt>
            <dd class="support__value">平日（土日祝を除く）</dd>
          </div>
          <div class="support__item">
            <dt class="support__key">ご返信</dt>
            <dd class="support__value">1営業日以内</dd>
          </div>
          <!-- 修正の日数は「制作中」と「保守」で分ける（制作中のデザイン修正は量が多いこともあるため） -->
          <div class="support__item">
            <dt class="support__key">制作中の修正</dt>
            <dd class="support__value">内容を確認して、対応日数の目安をお伝えします（目安：3営業日以内）</dd>
          </div>
          <div class="support__item">
            <dt class="support__key">保守の軽微な修正</dt>
            <dd class="support__value">3営業日以内</dd>
          </div>
        </dl>
      </section>
    </div>
  </div>

  <!-- ========== 制作の流れ ========== -->
  <section class="section" id="flow" aria-labelledby="flow-title">
    <div class="container">
      <div class="section__head">
        <h2 class="section__title" id="flow-title">制作の流れ</h2>
      </div>

      <!-- アイコンの素（Remix Icon。Webサイトでの利用は無料・クレジット表記不要）
           ここで1回だけ定義し、各ステップでは <use href="#〇〇"> で呼び出す。
           fill="currentColor" なので、アイコンの色は CSS の color で変えられる（ダークモードにも追従）
           ※ hidden 属性はSVGには効かない（初期サイズ300×150の場所をとる）ので、.visually-hidden で隠す -->
      <svg class="visually-hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
        <symbol id="flow-icon-mail" viewBox="0 0 24 24"><path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM20 7.23792L12.0718 14.338L4 7.21594V19H20V7.23792ZM4.51146 5L12.0619 11.662L19.501 5H4.51146Z"/></symbol>
        <symbol id="flow-icon-chat" viewBox="0 0 24 24"><path d="M7.29117 20.8242L2 22L3.17581 16.7088C2.42544 15.3056 2 13.7025 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C10.2975 22 8.6944 21.5746 7.29117 20.8242ZM7.58075 18.711L8.23428 19.0605C9.38248 19.6745 10.6655 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 13.3345 4.32549 14.6175 4.93949 15.7657L5.28896 16.4192L4.63416 19.3658L7.58075 18.711Z"/></symbol>
        <symbol id="flow-icon-design" viewBox="0 0 24 24"><path d="M7.05033 14.1213L4.929 16.2427L7.75743 19.0711L19.0711 7.75737L16.2427 4.92894L14.1214 7.05026L15.5356 8.46448L14.1214 9.87869L12.7072 8.46448L11.293 9.87869L12.7072 11.2929L11.293 12.7071L9.87875 11.2929L8.46454 12.7071L9.87875 14.1213L8.46454 15.5355L7.05033 14.1213ZM16.9498 2.80762L21.1925 7.05026C21.583 7.44079 21.583 8.07395 21.1925 8.46448L8.46454 21.1924C8.07401 21.5829 7.44085 21.5829 7.05033 21.1924L2.80768 16.9498C2.41716 16.5592 2.41716 15.9261 2.80768 15.5355L15.5356 2.80762C15.9261 2.4171 16.5593 2.4171 16.9498 2.80762ZM14.1214 18.3635L15.5356 16.9493L17.7781 19.1918H19.1923V17.7776L16.9498 15.5351L18.364 14.1208L20.9997 16.7565V20.9999H16.7578L14.1214 18.3635ZM5.63597 9.87806L2.80754 7.04963C2.41702 6.65911 2.41702 6.02594 2.80754 5.63542L5.63597 2.80699C6.02649 2.41647 6.65966 2.41647 7.05018 2.80699L9.87861 5.63542L8.4644 7.04963L6.34308 4.92831L4.92886 6.34253L7.05018 8.46385L5.63597 9.87806Z"/></symbol>
        <symbol id="flow-icon-code" viewBox="0 0 24 24"><path d="M24 12L18.3431 17.6569L16.9289 16.2426L21.1716 12L16.9289 7.75736L18.3431 6.34315L24 12ZM2.82843 12L7.07107 16.2426L5.65685 17.6569L0 12L5.65685 6.34315L7.07107 7.75736L2.82843 12ZM9.78845 21H7.66009L14.2116 3H16.3399L9.78845 21Z"/></symbol>
        <symbol id="flow-icon-check" viewBox="0 0 24 24"><path d="M4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12ZM12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM17.4571 9.45711L16.0429 8.04289L11 13.0858L8.20711 10.2929L6.79289 11.7071L11 15.9142L17.4571 9.45711Z"/></symbol>
        <symbol id="flow-icon-global" viewBox="0 0 24 24"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM9.71002 19.6674C8.74743 17.6259 8.15732 15.3742 8.02731 13H4.06189C4.458 16.1765 6.71639 18.7747 9.71002 19.6674ZM10.0307 13C10.1811 15.4388 10.8778 17.7297 12 19.752C13.1222 17.7297 13.8189 15.4388 13.9693 13H10.0307ZM19.9381 13H15.9727C15.8427 15.3742 15.2526 17.6259 14.29 19.6674C17.2836 18.7747 19.542 16.1765 19.9381 13ZM4.06189 11H8.02731C8.15732 8.62577 8.74743 6.37407 9.71002 4.33256C6.71639 5.22533 4.458 7.8235 4.06189 11ZM10.0307 11H13.9693C13.8189 8.56122 13.1222 6.27025 12 4.24799C10.8778 6.27025 10.1811 8.56122 10.0307 11ZM14.29 4.33256C15.2526 6.37407 15.8427 8.62577 15.9727 11H19.9381C19.542 7.8235 17.2836 5.22533 14.29 4.33256Z"/></symbol>
      </svg>

      <ol class="flow">
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 1</span>
            <h3 class="flow__title">お問い合わせ</h3>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-mail"/></svg></span>
          <p class="flow__text">お問い合わせフォームから、ご依頼内容をお送りください。1営業日以内にご返信します。</p>
        </li>
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 2</span>
            <h3 class="flow__title">ヒアリング・お見積もり</h3>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-chat"/></svg></span>
          <p class="flow__text">ご希望の内容をお聞きし、お見積もりをお送りします。</p>
        </li>
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 3</span>
            <h3 class="flow__title">構成・デザイン</h3>
            <p class="pill-tag">デザイン込みのプラン</p>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-design"/></svg></span>
          <p class="flow__text">構成やデザインの方向性をワイヤーフレーム（配置の設計図）ですり合わせてから、デザインをお作りします。デザインの修正は、完成後2回まで無料です。</p>
        </li>
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 4</span>
            <h3 class="flow__title">コーディング</h3>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-code"/></svg></span>
          <p class="flow__text">デザインをもとに、スマホ表示にも対応したページを作ります。</p>
        </li>
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 5</span>
            <h3 class="flow__title">ご確認・修正</h3>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-check"/></svg></span>
          <p class="flow__text">できあがったページをご確認いただきます。修正は内容を確認して、対応日数の目安をお伝えします（目安：3営業日以内）。</p>
        </li>
        <li class="flow__step">
          <div class="flow__head">
            <span class="flow__number" aria-hidden="true">STEP 6</span>
            <h3 class="flow__title">公開</h3>
          </div>
          <span class="flow__icon" aria-hidden="true"><svg class="flow__icon-svg" fill="currentColor"><use href="#flow-icon-global"/></svg></span>
          <p class="flow__text">公開作業の代行や、公開後の保守もお受けしています。</p>
        </li>
      </ol>

      <div class="cta">
        <p class="cta__text"><strong class="cta__lead">仕様が決まる前のご相談も受け付けています。</strong>ご希望の内容とご予算をお聞かせいただければ、対応範囲とお見積もりをお返しします。</p>
        <a class="button button--primary cta__button" href="<?php echo esc_url(home_url('/#contact')); ?>">相談してみる</a>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
