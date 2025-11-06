<!doctype html>
<html <?php language_attributes(); ?> >
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="container header">
<div class="branding">
<?php if(function_exists('the_custom_logo') && has_custom_logo()){the_custom_logo();} else { ?>
<a class="site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
<?php } ?>
</div>
<nav class="nav">
<?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'items_wrap'=>'%3$s']); ?>
</nav>
</header>
<main class="container">