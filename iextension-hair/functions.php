<?php
// Theme functions for Hair Extension Store + Booking System

if ( ! function_exists( 'hes_setup' ) ) {
    function hes_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'hes' ),
        ) );
    }
}
add_action( 'after_setup_theme', 'hes_setup' );

function hes_enqueue_scripts() {
    wp_enqueue_style( 'hes-style', get_stylesheet_uri() );
    wp_enqueue_script( 'hes-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true );
}
add_action( 'wp_enqueue_scripts', 'hes_enqueue_scripts' );

// Register Custom Post Type: Extensions (products)
function hes_register_extension_cpt() {
    $labels = array(
        'name' => 'Extensions',
        'singular_name' => 'Extension',
    );
    $args = array(
        'label' => 'Extensions',
        'public' => true,
        'supports' => array('title','editor','thumbnail','custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'extensions'),
    );
    register_post_type( 'extension', $args );
}
add_action( 'init', 'hes_register_extension_cpt' );

// Handle booking form submissions and save as a custom post type 'booking'
function hes_register_booking_cpt() {
    $args = array(
        'label' => 'Bookings',
        'public' => false,
        'show_ui' => true,
        'supports' => array('title','editor','custom-fields'),
    );
    register_post_type( 'hes_booking', $args );
}
add_action( 'init', 'hes_register_booking_cpt' );

function hes_handle_booking_submission() {
    // nonce check
    if ( ! isset($_POST['hes_nonce']) || ! wp_verify_nonce( $_POST['hes_nonce'], 'hes_book_action' ) ) {
        wp_die('Security check failed.');
    }
    $name = sanitize_text_field( $_POST['hes_name'] ?? '' );
    $email = sanitize_email( $_POST['hes_email'] ?? '' );
    $service = sanitize_text_field( $_POST['hes_service'] ?? '' );
    $date = sanitize_text_field( $_POST['hes_date'] ?? '' );
    $notes = sanitize_textarea_field( $_POST['hes_notes'] ?? '' );

    $postarr = array(
        'post_type' => 'hes_booking',
        'post_title' => $name . ' — ' . $service . ' — ' . $date,
        'post_content' => "Client: {$name}\nEmail: {$email}\nService: {$service}\nDate: {$date}\nNotes: {$notes}",
        'post_status' => 'publish',
    );
    $post_id = wp_insert_post( $postarr );
    if ( $post_id ) {
        add_post_meta( $post_id, 'hes_client_email', $email );
        add_post_meta( $post_id, 'hes_service', $service );
        add_post_meta( $post_id, 'hes_date', $date );
    }
    wp_redirect( add_query_arg('hes_booking','success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_nopriv_hes_book', 'hes_handle_booking_submission' );
add_action( 'admin_post_hes_book', 'hes_handle_booking_submission' );

// Simple shortcode to show booking form
function hes_booking_form_shortcode() {
    ob_start();
    ?>
    <form class="booking-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <?php wp_nonce_field( 'hes_book_action', 'hes_nonce' ); ?>
        <input type="hidden" name="action" value="hes_book" />
        <p><label>Name<br /><input type="text" name="hes_name" required /></label></p>
        <p><label>Email<br /><input type="email" name="hes_email" required /></label></p>
        <p><label>Service<br /><input type="text" name="hes_service' /></label></p>
        <p><label>Date & Time<br /><input type="datetime-local" name="hes_date" required /></label></p>
        <p><label>Notes<br /><textarea name="hes_notes"></textarea></label></p>
        <p><button type="submit">Request Booking</button></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('hes_booking_form','hes_booking_form_shortcode');
