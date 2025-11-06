<?php get_header(); ?>

<section class="hero">
  <h2>Luxury Hair Extensions for Every Look ✨</h2>
</section>

<section class="section">
  <h3>Welcome to iExtension</h3>
  <p>Discover premium-quality extensions that elevate your style. Book your appointment today and experience the difference.</p>
  <a href="#" class="button">Book Now</a>
</section>

<section class="section">
  <h3>Our Products</h3>
  <?php
  $args = array('post_type' => 'post', 'posts_per_page' => 3);
  $query = new WP_Query($args);
  if ($query->have_posts()) :
    echo '<div class="products">';
    while ($query->have_posts()) : $query->the_post(); ?>
      <div class="product">
        <?php the_post_thumbnail('medium'); ?>
        <h4><?php the_title(); ?></h4>
        <p><?php the_excerpt(); ?></p>
      </div>
    <?php endwhile;
    echo '</div>';
    wp_reset_postdata();
  endif;
  ?>
</section>

<section class="section" id="booking">
  <h3>Book an Appointment</h3>

  <?php if (isset($_GET['booked']) && $_GET['booked'] == 'success') : ?>
    <p style="color: green;">✅ Thank you! Your booking request has been sent.</p>
  <?php endif; ?>

  <form method="post" style="max-width:600px; margin:auto; text-align:left;">
    <label>Name:</label><br>
    <input type="text" name="name" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

    <label>Email:</label><br>
    <input type="email" name="email" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

    <label>Service:</label><br>
    <select name="service" style="width:100%; padding:8px; margin-bottom:10px;">
      <option value="Hair Extensions">Hair Extensions</option>
      <option value="Consultation">Consultation</option>
      <option value="Hair Styling">Hair Styling</option>
    </select><br>

    <label>Preferred Date:</label><br>
    <input type="date" name="date" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

    <label>Message:</label><br>
    <textarea name="message" rows="4" style="width:100%; padding:8px; margin-bottom:10px;"></textarea><br>

    <button type="submit" name="iextension_booking_submit" class="button">Submit Booking</button>
  </form>
</section>


<?php get_footer(); ?>
