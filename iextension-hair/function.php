<?php
// theme setup
add_action('after_setup_theme', function(){
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo');
add_theme_support('html5', ['search-form','gallery','caption']);
register_nav_menus(['primary'=>'Primary Menu']);
});


// enqueue styles & scripts
add_action('wp_enqueue_scripts', function(){
wp_enqueue_style('iext-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
wp_enqueue_script('iext-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], null, true);
});


// helper: excerpt length
add_filter('excerpt_length', function($len){return 18;});


// Register widget area
add_action('widgets_init', function(){
register_sidebar([
'name'=>'Sidebar',
'id'=>'sidebar-1',
'before_widget'=>'<aside class="widget card">',
'after_widget'=>'</aside>',
'before_title'=>'<h3 class="widget-title">',
'after_title'=>'</h3>'
]);
});


// Simple demo customizer setting
add_action('customize_register', function($wp_customize){
$wp_customize->add_section('iext_brand', ['title'=>'Branding']);
$wp_customize->add_setting('iext_accent', ['default'=>'#b565a7']);
$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize,'iext_accent_control',[
'label'=>'Accent color','section'=>'iext_brand','settings'=>'iext_accent'
]));
});


// THEME SETUP
function iextension_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'iextension')
    ));
}
add_action('after_setup_theme', 'iextension_setup');

function iextension_scripts() {
    wp_enqueue_style('iextension-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'iextension_scripts');


// ✅ CREATE BOOKINGS TABLE ON THEME ACTIVATION
function iextension_create_booking_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'iextension_bookings';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        service varchar(100) NOT NULL,
        date varchar(50) NOT NULL,
        message text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'iextension_create_booking_table');


// ✅ HANDLE BOOKING SUBMISSION
function iextension_handle_booking() {
    if (isset($_POST['iextension_booking_submit'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'iextension_bookings';

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $service = sanitize_text_field($_POST['service']);
        $date = sanitize_text_field($_POST['date']);
        $message = sanitize_textarea_field($_POST['message']);

        // Insert into database
        $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'service' => $service,
            'date' => $date,
            'message' => $message
        ));

        // Send email notification
        $to = get_option('admin_email');
        $subject = 'New Booking Request from ' . $name;
        $body = "Name: $name\nEmail: $email\nService: $service\nDate: $date\nMessage:\n$message";
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        wp_mail($to, $subject, $body, $headers);

        // Redirect after submit
        wp_redirect(add_query_arg('booked', 'success', wp_get_referer()));
        exit;
    }
}
add_action('init', 'iextension_handle_booking');


// ✅ ADMIN PAGE FOR BOOKINGS
function iextension_register_booking_menu() {
    add_menu_page(
        'Bookings',
        'Bookings',
        'manage_options',
        'iextension_bookings',
        'iextension_display_bookings',
        'dashicons-calendar-alt',
        25
    );
}
add_action('admin_menu', 'iextension_register_booking_menu');

function iextension_display_bookings() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'iextension_bookings';

    // Handle Delete
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['booking_id'])) {
        $id = intval($_GET['booking_id']);
        $wpdb->delete($table_name, ['id' => $id]);
        echo '<div class="notice notice-success"><p>Booking deleted successfully.</p></div>';
    }

    // Handle Update
    if (isset($_POST['iextension_update_booking'])) {
        $id = intval($_POST['booking_id']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $service = sanitize_text_field($_POST['service']);
        $date = sanitize_text_field($_POST['date']);
        $message = sanitize_textarea_field($_POST['message']);

        $wpdb->update($table_name, [
            'name' => $name,
            'email' => $email,
            'service' => $service,
            'date' => $date,
            'message' => $message
        ], ['id' => $id]);

        echo '<div class="notice notice-success"><p>Booking updated successfully.</p></div>';
    }

    // If editing a booking, show the form
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['booking_id'])) {
        $id = intval($_GET['booking_id']);
        $booking = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if ($booking) {
            ?>
            <div class="wrap">
                <h1>Edit Booking</h1>
                <form method="post">
                    <input type="hidden" name="booking_id" value="<?php echo $booking->id; ?>">
                    <table class="form-table">
                        <tr>
                            <th>Name</th>
                            <td><input type="text" name="name" value="<?php echo esc_attr($booking->name); ?>" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><input type="email" name="email" value="<?php echo esc_attr($booking->email); ?>" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>
                                <select name="service">
                                    <option value="Hair Extensions" <?php selected($booking->service, 'Hair Extensions'); ?>>Hair Extensions</option>
                                    <option value="Consultation" <?php selected($booking->service, 'Consultation'); ?>>Consultation</option>
                                    <option value="Hair Styling" <?php selected($booking->service, 'Hair Styling'); ?>>Hair Styling</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><input type="date" name="date" value="<?php echo esc_attr($booking->date); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Message</th>
                            <td><textarea name="message" rows="5" class="large-text"><?php echo esc_textarea($booking->message); ?></textarea></td>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" name="iextension_update_booking" class="button button-primary" value="Update Booking">
                        <a href="<?php echo admin_url('admin.php?page=iextension_bookings'); ?>" class="button">Cancel</a>
                    </p>
                </form>
            </div>
            <?php
            return; // Stop showing the table below
        }
    }

    // Show all bookings table
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    ?>
    <div class="wrap">
        <h1>Client Bookings</h1>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Message</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results): foreach ($results as $row): ?>
                <tr>
                    <td><?php echo esc_html($row->name); ?></td>
                    <td><?php echo esc_html($row->email); ?></td>
                    <td><?php echo esc_html($row->service); ?></td>
                    <td><?php echo esc_html($row->date); ?></td>
                    <td><?php echo esc_html($row->message); ?></td>
                    <td><?php echo esc_html($row->created_at); ?></td>
                    <td>
                        <a class="button" href="<?php echo admin_url('admin.php?page=iextension_bookings&action=edit&booking_id='.$row->id); ?>">Edit</a>
                        <a class="button" style="background:#dc3232;" href="<?php echo admin_url('admin.php?page=iextension_bookings&action=delete&booking_id='.$row->id); ?>" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7">No bookings yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}



?>
