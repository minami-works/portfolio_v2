/* =========================================================
   minamiworks v2 main.js
   1. ハンバーガーメニュー
   2. ダークモード切り替え
   3. Works の絞り込み
   3-2. お問い合わせフォームの二重送信防止
   4. 画面キャプチャの拡大表示（詳細ページ）
   ========================================================= */
document.addEventListener('DOMContentLoaded', () => {
  /* ---------- 1. ハンバーガーメニュー ---------- */
  const hamburger = document.querySelector('.js-hamburger');
  const nav = document.getElementById('global-nav');

  if (hamburger && nav) {
    const setMenu = (open) => {
      document.body.classList.toggle('is-menu-open', open);
      hamburger.setAttribute('aria-expanded', String(open));
      hamburger.setAttribute('aria-label', open ? 'メニューを閉じる' : 'メニューを開く');
    };
    hamburger.addEventListener('click', () => {
      setMenu(hamburger.getAttribute('aria-expanded') !== 'true');
    });
    // メニュー内のリンクを押したら閉じる
    nav.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => setMenu(false));
    });
    // Escキーで閉じる
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') setMenu(false);
    });
  }

  /* ---------- 2. ダークモード切り替え ---------- */
  // 最初のクラス付けは functions.php の <head> 内スクリプトで実施済み
  const themeToggle = document.querySelector('.js-theme-toggle');
  const root = document.documentElement;

  if (themeToggle) {
    const updateLabel = () => {
      const isDark = root.classList.contains('is-dark');
      themeToggle.setAttribute('aria-label', isDark ? 'ライトモードに切り替え' : 'ダークモードに切り替え');
    };
    updateLabel();
    themeToggle.addEventListener('click', () => {
      // 切り替えの瞬間だけ全要素の transition を止める（色が一斉にバラバラ変わって、にじんで見えるのを防ぐ）
      // ただし月⇔太陽アイコンのクロスフェードだけは動かしたいので除外する
      const noTransition = document.createElement('style');
      noTransition.textContent = '*:not(.theme-toggle__icon),*::before,*::after{transition:none !important}';
      document.head.append(noTransition);

      const isDark = root.classList.toggle('is-dark');

      // スマホのブラウザ上部の色も、ページの背景色に合わせて切り替える
      const themeColor = document.querySelector('meta[name="theme-color"]');
      if (themeColor) themeColor.setAttribute('content', isDark ? '#1f2420' : '#f7f7f3');

      void document.body.offsetHeight; // ここで新しい色を確定させる
      requestAnimationFrame(() => {
        requestAnimationFrame(() => noTransition.remove()); // 描画が終わってから元に戻す
      });
      try {
        localStorage.setItem('mw2-theme', isDark ? 'dark' : 'light');
      } catch (e) {
        /* 保存できない環境では何もしない */
      }
      updateLabel();
    });
  }

  /* ---------- 3. Works の絞り込み ---------- */
  const filter = document.querySelector('.js-works-filter');
  const cards = document.querySelectorAll('.js-work-card');
  const empty = document.querySelector('.js-works-empty');
  const status = document.querySelector('.js-works-status');

  if (filter && cards.length) {
    const buttons = filter.querySelectorAll('[data-filter]');

    filter.addEventListener('click', (e) => {
      const button = e.target.closest('[data-filter]');
      if (!button) return;
      const value = button.dataset.filter;

      buttons.forEach((b) => {
        const active = b === button;
        b.classList.toggle('is-active', active);
        b.setAttribute('aria-pressed', String(active));
      });

      let visible = 0;
      cards.forEach((card) => {
        const tags = card.dataset.tags.split(' ');
        const show = value === 'all' || tags.includes(value);
        card.hidden = !show;
        if (show) visible++;
      });
      if (empty) empty.hidden = visible > 0;
      // 画面読み上げ用：role="status" の中身を書き換えると、その文が読み上げられる
      if (status) {
        status.textContent = visible > 0
          ? `${button.textContent.trim()}：${visible}件の実績を表示しています`
          : 'この条件に合う実績はありません';
      }
    });
  }

  /* ---------- 3-2. お問い合わせフォームの二重送信を防ぐ ---------- */
  // submit イベントは、required などの入力チェックを通った時だけ発生する
  const contactForm = document.querySelector('.js-contact-form');

  if (contactForm) {
    const submitButton = contactForm.querySelector('[type="submit"]');
    const submitLabel = submitButton.textContent;

    contactForm.addEventListener('submit', () => {
      // 送信が始まったらボタンを押せなくする（送信そのものは止まらない）
      submitButton.disabled = true;
      submitButton.textContent = '送信中…';
    });

    // 送信後にブラウザの「戻る」で戻ってきた時、ボタンが押せないままにならないよう元に戻す
    window.addEventListener('pageshow', () => {
      submitButton.disabled = false;
      submitButton.textContent = submitLabel;
    });
  }

  /* ---------- 4. 画面キャプチャの拡大表示 ---------- */
  /* ※ 各セクションのフェードインは「よくある演出」に見えるため廃止。
       動きはヒーローの読み込み時だけ（scss/_animation.scss） */
  const lightbox = document.querySelector('.js-lightbox');

  if (lightbox && typeof lightbox.showModal === 'function') {
    const lightboxImg = lightbox.querySelector('img');

    document.querySelectorAll('.js-zoom').forEach((button) => {
      button.addEventListener('click', () => {
        const img = button.querySelector('img');
        lightboxImg.src = button.dataset.src;
        lightboxImg.alt = img ? img.alt : '';
        lightbox.showModal();
      });
    });
    lightbox.querySelector('.js-lightbox-close').addEventListener('click', () => lightbox.close());
    // 画像の外（背景）をクリックしたら閉じる
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) lightbox.close();
    });
  }
});
