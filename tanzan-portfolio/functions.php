<?php
if (!defined('ABSPATH')) { exit; }
function tanzan_setup() {
 add_theme_support('title-tag');
 add_theme_support('post-thumbnails');
 add_theme_support('html5', array('search-form','gallery','caption','style','script'));
 register_nav_menus(array('primary' => 'ヘッダーナビゲーション'));
}
add_action('after_setup_theme', 'tanzan_setup');
function tanzan_assets() {
 wp_enqueue_style('tanzan-style', get_stylesheet_uri(), array(), filemtime(get_theme_file_path('/style.css')));
 wp_enqueue_script('tanzan-main', get_theme_file_uri('/assets/main.js'), array(), filemtime(get_theme_file_path('/assets/main.js')), true);
}
add_action('wp_enqueue_scripts', 'tanzan_assets');
function tanzan_register_works() {
 register_post_type('works', array('labels'=>array('name'=>'制作実績','singular_name'=>'制作実績','add_new_item'=>'制作実績を追加','edit_item'=>'制作実績を編集'), 'public'=>true, 'show_in_rest'=>true, 'has_archive'=>true, 'rewrite'=>array('slug'=>'works'), 'menu_icon'=>'dashicons-portfolio', 'supports'=>array('title','editor','thumbnail','excerpt','revisions')));
 foreach (array('technology','period','role','project_url') as $key) {
  register_post_meta('works', '_tanzan_'.$key, array('type'=>'string','single'=>true,'show_in_rest'=>false,'sanitize_callback'=> $key === 'project_url' ? 'esc_url_raw' : 'sanitize_text_field','auth_callback'=>function(){return current_user_can('edit_posts');}));
 }
}
add_action('init', 'tanzan_register_works');
function tanzan_activate() { tanzan_register_works(); flush_rewrite_rules(); }
add_action('after_switch_theme','tanzan_activate');
function tanzan_work_fields() { add_meta_box('tanzan-work-details','制作実績の詳細','tanzan_work_box','works','normal','default'); }
add_action('add_meta_boxes','tanzan_work_fields');
function tanzan_work_box($post) {
 wp_nonce_field('tanzan_save_work','tanzan_work_nonce');
 foreach (array('technology'=>'使用技術','period'=>'制作期間','role'=>'担当範囲','project_url'=>'公開URL') as $key=>$label) {
  echo '<p><label for="tanzan-'.esc_attr($key).'">'.esc_html($label).'</label><br><input style="width:100%" id="tanzan-'.esc_attr($key).'" name="tanzan_'.esc_attr($key).'" type="'.esc_attr($key==='project_url'?'url':'text').'" value="'.esc_attr(get_post_meta($post->ID,'_tanzan_'.$key,true)).'"></p>';
 }
}
function tanzan_save_work($post_id) {
 if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {return;}
 if (wp_is_post_revision($post_id) || !current_user_can('edit_post',$post_id)) {return;}
 if (!isset($_POST['tanzan_work_nonce']) || !is_string($_POST['tanzan_work_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tanzan_work_nonce'])),'tanzan_save_work')) {return;}
 foreach (array('technology','period','role','project_url') as $key) {
  if (isset($_POST['tanzan_'.$key]) && is_string($_POST['tanzan_'.$key])) {
   $value=wp_unslash($_POST['tanzan_'.$key]);
   update_post_meta($post_id,'_tanzan_'.$key,$key==='project_url'?esc_url_raw($value):sanitize_text_field($value));
  }
 }
}
add_action('save_post_works','tanzan_save_work');
function tanzan_customize($manager) {
 $manager->add_section('tanzan_hero',array('title'=>'メインビジュアル'));
 $manager->add_setting('tanzan_hero_image',array('default'=>'','sanitize_callback'=>'esc_url_raw'));
 $manager->add_control(new WP_Customize_Image_Control($manager,'tanzan_hero_image',array('label'=>'背景画像','description'=>'未設定時は同梱の本棚イラストを表示します。横長の画像を推奨します。','section'=>'tanzan_hero')));

 $manager->add_section('tanzan_contact',array('title'=>'お問い合わせフォーム'));
 $manager->add_setting('tanzan_cf7_id',array('default'=>0,'sanitize_callback'=>'absint'));
 $manager->add_control('tanzan_cf7_id',array('label'=>'Contact Form 7 のフォームID（数字）','section'=>'tanzan_contact','type'=>'number','input_attrs'=>array('min'=>0)));
}
add_action('customize_register','tanzan_customize');
function tanzan_fallback_menu() {
 echo '<ul class="menu">';
 foreach(array('about'=>'About','skills'=>'Skills','works'=>'Works','contact'=>'Contact') as $id=>$label){echo '<li><a href="'.esc_url(home_url('/').'#'.$id).'">'.esc_html($label).'</a></li>';}
 echo '</ul>';
}
