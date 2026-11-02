<?php
/**
 * Seed the Harbour & Hearth A3 backend in a LocalWP site.
 *
 * Run from the LocalWP site shell:
 *   wp eval-file /path/to/provided/a3-harbour-hearth-starter/wp-backup/seed-localwp.php
 *
 * This writes the mu-plugin, pages, posts, bookings and the olddev account.
 * It does not install plugins or create the .wpress. See README.md.
 */

require_once ABSPATH . 'wp-admin/includes/file.php';

$plugin_src = __DIR__ . '/mu-plugin.php';
$plugin_dst = WP_CONTENT_DIR . '/mu-plugins/hh-a3.php';

if (!file_exists($plugin_src)) {
    echo "Cannot find mu-plugin.php next to this script.\n";
    return;
}

wp_mkdir_p(dirname($plugin_dst));
copy($plugin_src, $plugin_dst);
echo "Mu-plugin copied to {$plugin_dst}.\n";

update_option('blogname', 'Harbour & Hearth');
update_option('blogdescription', 'Coffee, bread and private events');
update_option('permalink_structure', '/%postname%/');

$pages = [
    'News' => 'What is happening at Harbour & Hearth.',
    'Events' => 'Private events and room hire.',
    'Bookings' => 'Table and event bookings.',
    'Contact' => 'Find us and get in touch.',
];

foreach ($pages as $title => $content) {
    if (!get_page_by_path(sanitize_title($title))) {
        wp_insert_post([
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page',
        ]);
        echo "Created page: {$title}\n";
    }
}

$news = [
    ['Winter menu is here', 'The new winter menu starts this week.'],
    ['Private room refresh', 'The Claremont room has been repainted and reseated.'],
];

foreach ($news as $item) {
    if (!get_page_by_path(sanitize_title($item[0]), OBJECT, 'post')) {
        wp_insert_post([
            'post_title' => $item[0],
            'post_content' => $item[1],
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);
        echo "Created post: {$item[0]}\n";
    }
}

// The mu-plugin loads on the next request. Register the type here so the
// sample bookings below are inserted in this request too.
register_post_type('booking', [
    'label' => 'Bookings',
    'public' => true,
    'show_in_rest' => true,
    'supports' => ['title', 'editor', 'custom-fields'],
]);

$bookings = [
    ['Sam Taylor', '0400 000 001', 'sam@example.com', 'Table for two, Friday'],
    ['Alex Chen', '0400 000 002', 'alex@example.com', 'Private event, 20 guests'],
    ['Priya Singh', '0400 000 003', 'priya@example.com', 'Corporate lunch, 12 guests'],
];

foreach ($bookings as $booking) {
    $existing = get_posts([
        'post_type' => 'booking',
        'title' => $booking[0],
        'numberposts' => 1,
    ]);

    if (!$existing) {
        $id = wp_insert_post([
            'post_title' => $booking[0],
            'post_content' => $booking[3],
            'post_status' => 'publish',
            'post_type' => 'booking',
        ]);
        update_post_meta($id, 'phone', $booking[1]);
        update_post_meta($id, 'email', $booking[2]);
        echo "Created booking: {$booking[0]}\n";
    }
}

if (!username_exists('olddev')) {
    $uid = wp_create_user('olddev', 'olddev-pass-2024', 'olddev@example.com');
    if (!is_wp_error($uid)) {
        $user = new WP_User($uid);
        $user->set_role('administrator');
        echo "Created olddev administrator.\n";
    }
}

flush_rewrite_rules();
echo "Seed complete.\n";
echo "Next: install All-in-One WP Migration and export to .wpress. See README.md.\n";
