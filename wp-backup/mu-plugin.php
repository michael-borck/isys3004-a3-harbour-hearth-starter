<?php
/*
Plugin Name: Harbour & Hearth A3 Backend
Description: News, team and bookings for the inherited site.
Version: 1.0
*/

add_action('init', function () {
    register_post_type('booking', [
        'label' => 'Bookings',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('hh/v1', '/team', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            return [
                ['name' => 'Maria Rossi', 'suburb' => 'Claremont'],
                ['name' => 'Dev Patel', 'suburb' => 'Northbridge'],
                ['name' => 'Sam Taylor', 'suburb' => 'Fremantle'],
            ];
        },
    ]);

    register_rest_route('hh/v1', '/bookings', [
        [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => function () {
                $posts = get_posts([
                    'post_type' => 'booking',
                    'numberposts' => 20,
                ]);

                return array_map(function ($post) {
                    return [
                        'id' => $post->ID,
                        'name' => $post->post_title,
                        'phone' => get_post_meta($post->ID, 'phone', true),
                        'email' => get_post_meta($post->ID, 'email', true),
                        'notes' => $post->post_content,
                    ];
                }, $posts);
            },
        ],
        [
            'methods' => 'POST',
            'permission_callback' => '__return_true',
            'callback' => function ($request) {
                $params = $request->get_json_params();
                if (!$params) {
                    $params = $request->get_params();
                }

                $id = wp_insert_post([
                    'post_title' => $params['name'] ?? 'Booking',
                    'post_content' => $params['notes'] ?? '',
                    'post_status' => 'publish',
                    'post_type' => 'booking',
                ]);

                update_post_meta($id, 'phone', $params['phone'] ?? '');
                update_post_meta($id, 'email', $params['email'] ?? '');

                return ['id' => $id, 'status' => 'received'];
            },
        ],
    ]);
});
