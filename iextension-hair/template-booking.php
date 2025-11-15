<?php
get_header(); ?>
<div class="container">
    <h1>Advanced Booking Page</h1>
    <p>This is an additional booking page with the same form but a new layout.</p>

    <div style="background:#fff; padding:20px; border-radius:8px; border:1px solid #ddd;">
        <?php echo do_shortcode('[hes_booking_form]'); ?>
    </div>

    <?php if(isset($_GET['hes_booking']) && $_GET['hes_booking']=='success'): ?>
        <p style="margin-top:20px; color:green;"><strong>Your booking request was submitted successfully.</strong></p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
