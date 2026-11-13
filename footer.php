<?php $home = is_front_page() ? '' : home_url('/'); ?>
<footer class="footer">
  <div class="footer__inner">
    <p class="footer__copy">&copy; <?php echo esc_html(date('Y')); ?> Minami Gouda</p>
    <ul class="footer__nav">
      <?php foreach (array('works' => '制作実績', 'pricing' => '料金', 'contact' => 'お問い合わせ') as $id => $label) : ?>
        <li><a class="footer__link" href="<?php echo esc_url($home . '#' . $id); ?>"><?php echo esc_html($label); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
