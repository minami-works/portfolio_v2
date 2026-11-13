<?php
/**
 * Works 用 ACF フィールド（ACF Free で動く項目のみ。リピーターは使っていません）
 *
 * - 既存サイトと同じフィールド名（work_ja / role / tech / period / github_url / site_url /
 *   site_label / image_pc / image_sp / overview / overview_note / points）をそのまま使っているので、
 *   既存データを移しても表示されます。
 * - 新しく増えたのは scope（担当範囲）と screen_〇〇（画面キャプチャ枠）です。
 * - ここで定義したフィールドは「ACF > フィールドグループ」には表示されません（コード管理）。
 *   管理画面で編集したくなったら、このファイルを読み込まないようにして ACF側で作り直してください。
 */
if (!function_exists('acf_add_local_field_group')) return;

function mw2_acf_image($key, $name, $label, $instructions = '') {
  return array(
    'key' => $key, 'name' => $name, 'label' => $label, 'type' => 'image',
    'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => $instructions,
  );
}
function mw2_acf_text($key, $name, $label, $instructions = '', $type = 'text') {
  return array('key' => $key, 'name' => $name, 'label' => $label, 'type' => $type, 'instructions' => $instructions);
}

acf_add_local_field_group(array(
  'key'      => 'group_mw2_works',
  'title'    => 'Works 詳細',
  'location' => array(array(array('param' => 'post_type', 'operator' => '==', 'value' => 'works'))),
  'position' => 'normal',
  'fields'   => array(
    array('key' => 'tab_mw2_basic', 'label' => '基本情報', 'type' => 'tab'),
    mw2_acf_text('field_mw2_work_ja', 'work_ja', 'サブタイトル（日本語）', '例：書店サイト・コーディング'),
    array(
      'key' => 'field_mw2_role', 'name' => 'role', 'label' => '担当（role）', 'type' => 'checkbox',
      'choices' => array(
        'コーディング' => 'コーディング',
        'WordPress化'  => 'WordPress化',
        'JS実装'       => 'JS実装',
      ),
      'allow_custom' => 1, 'save_custom' => 1, 'return_format' => 'value', 'layout' => 'horizontal',
    ),
    mw2_acf_text('field_mw2_tech', 'tech', '使用技術（tech）', '例：HTML / SCSS / JavaScript'),
    mw2_acf_text('field_mw2_period', 'period', '制作期間（period）', '例：2週間'),
    mw2_acf_image('field_mw2_image_pc', 'image_pc', 'メイン画像（PC）', '一覧のサムネイルと詳細ページ上部に表示（横長推奨）'),
    mw2_acf_image('field_mw2_image_sp', 'image_sp', 'メイン画像（SP）', '現在は未使用。必要になったら表示を追加'),
    mw2_acf_text('field_mw2_github_url', 'github_url', 'GitHub URL', '空欄ならリンク非表示', 'url'),
    mw2_acf_text('field_mw2_site_url', 'site_url', '公開サイト URL', '空欄ならリンク非表示', 'url'),
    mw2_acf_text('field_mw2_site_label', 'site_label', '公開サイトのリンク文言', '空欄なら「サイトを見る」'),

    array('key' => 'tab_mw2_detail', 'label' => '詳細ページ', 'type' => 'tab'),
    mw2_acf_text('field_mw2_overview', 'overview', '概要（overview）', '', 'textarea'),
    mw2_acf_text('field_mw2_overview_note', 'overview_note', '概要の補足', '小さめの文字で表示', 'textarea'),
    mw2_acf_text('field_mw2_scope', 'scope', '担当範囲（scope）', '1行に1項目', 'textarea'),
    mw2_acf_text('field_mw2_points', 'points', '工夫した点（notes）', '1行に1項目', 'textarea'),

    array('key' => 'tab_mw2_screens', 'label' => '画面キャプチャ', 'type' => 'tab'),
    mw2_acf_image('field_mw2_screen_pc_1', 'screen_pc_1', 'PC画面 1', '横幅いっぱいに表示'),
    mw2_acf_text('field_mw2_screen_pc_1_caption', 'screen_pc_1_caption', 'PC画面 1 の名前', '例：トップページ'),
    mw2_acf_image('field_mw2_screen_pc_2', 'screen_pc_2', 'PC画面 2'),
    mw2_acf_text('field_mw2_screen_pc_2_caption', 'screen_pc_2_caption', 'PC画面 2 の名前'),
    mw2_acf_image('field_mw2_screen_sp_1', 'screen_sp_1', 'SP画面 1', '縦長のスクリーンショット'),
    mw2_acf_text('field_mw2_screen_sp_1_caption', 'screen_sp_1_caption', 'SP画面 1 の名前'),
    mw2_acf_image('field_mw2_screen_sp_2', 'screen_sp_2', 'SP画面 2'),
    mw2_acf_text('field_mw2_screen_sp_2_caption', 'screen_sp_2_caption', 'SP画面 2 の名前'),
  ),
));
