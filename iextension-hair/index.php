<?php get_header(); ?>
<div class="container">
    <h1>Welcome to the Hair Extension Store</h1>
    <p>Check our latest extensions below.</p>
    <?php
    $args = array('post_type'=>'extension','posts_per_page'=>6);
    $loop = new WP_Query($args);
    if($loop->have_posts()):
        while($loop->have_posts()): $loop->the_post(); ?>
            <div class="product">
                <?php if(has_post_thumbnail()) { the_post_thumbnail('medium'); } ?>
                <div>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div><?php the_excerpt(); ?></div>
                </div>
            </div>
        <?php endwhile;
        wp_reset_postdata();
    else: ?>
        <p>No extensions found yet. Add some in the dashboard → Extensions.</p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
