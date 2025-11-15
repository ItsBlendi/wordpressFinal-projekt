<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="container">
        <div class="site-branding">
            <?php if ( function_exists( 'the_custom_logo' ) ) the_custom_logo(); ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url('/') ); ?>" style="color:inherit;"><?php bloginfo('name'); ?></a></h1>
        </div>
        <nav class="site-nav">
            <?php wp_nav_menu(array('theme_location'=>'primary')); ?>
        </nav>
    </div>
</header>
