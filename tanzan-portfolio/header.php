<?php if (!defined('ABSPATH')) {exit;} ?>
<!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?><a class="skip-link" href="#main">本文へ移動</a>
<header class="site-header"><div class="wrap header-inner"><a class="brand" href="<?php echo esc_url(home_url('/')); ?>">TANZAN BOOKS<span class="muted"> / PORTFOLIO</span></a><nav aria-label="メインナビゲーション"><?php wp_nav_menu(array('theme_location'=>'primary','container'=>false,'menu_class'=>'menu','depth'=>1,'fallback_cb'=>'tanzan_fallback_menu')); ?></nav></div></header>
