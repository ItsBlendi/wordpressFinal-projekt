<?php
/* Template Name: Home Template */
get_header(); ?>
<section class="hero">
<div>
<div class="eyebrow"><?php echo get_theme_mod('iext_accent') ? 'Featured' : 'Featured'; ?></div>
<h1><?php bloginfo('name'); ?> — Premium Hair Extensions</h1>
<p><?php bloginfo('description'); ?></p>
<a class="card" href="<?php echo esc_url(site_url('/shop')); ?>">Shop Now</a>
</div>
<aside class="card">
<?php if(function_exists('do_shortcode')) echo do_shortcode('[contact-form-7 id="123" title="Book"]'); ?>
</aside>
</section>


<section class="container">
<h2>Our Services</h2>
<div class="grid">
<div class="card"><h3>Installation</h3><p>Professional application by certified stylists.</p></div>
<div class="card"><h3>Color Match</h3><p>Free color match consultation on request.</p></div>
<div class="card"><h3>Custom Orders</h3><p>Order bespoke lengths and textures.</p></div>
</div>
</section>


<?php get_footer(); ?>