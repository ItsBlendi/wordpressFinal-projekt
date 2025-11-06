<?php get_header(); ?>


<section class="hero">
<div>
<div class="eyebrow">Premium Extensions</div>
<h1>Extensions that feel like your hair.</h1>
<p>Shop clip-ins, tape-ins and custom orders. Professional installation available — book an appointment below.</p>
<a class="card" href="<?php echo esc_url(site_url('/shop')); ?>">View Shop</a>
</div>
<aside class="card">
<h3>Book an Appointment</h3>
<p>Use our booking plugin or contact form to schedule your service.</p>
</aside>
</section>


<section class="container">
<h2>Latest products</h2>
<div class="grid">
<?php if(have_posts()): while(have_posts()): the_post(); ?>
<article class="product card">
<?php if(has_post_thumbnail()) the_post_thumbnail('medium'); ?>
<div class="meta">
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
<?php the_excerpt(); ?>
</div>
</article>
<?php endwhile; else: ?>
<p>No items found.</p>
<?php endif; wp_reset_postdata(); ?>
</div>
</section>


<?php get_footer(); ?>