#!/usr/bin/env python3
"""Build the WordPress Playground blueprint for the A3 backend."""

from __future__ import annotations

import base64
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent
plugin = base64.b64encode((ROOT / "mu-plugin.php").read_bytes()).decode("ascii")

php = """<?php
require_once ABSPATH . 'wp-admin/includes/file.php';

$plugin = base64_decode('""" + plugin + """');
wp_mkdir_p(WP_CONTENT_DIR . '/mu-plugins');
file_put_contents(WP_CONTENT_DIR . '/mu-plugins/hh-a3.php', $plugin);

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
    }
}

$bookings = [
    ['Sam Taylor', '0400 000 001', 'sam@example.com', 'Table for two, Friday'],
    ['Alex Chen', '0400 000 002', 'alex@example.com', 'Private event, 20 guests'],
    ['Priya Singh', '0400 000 003', 'priya@example.com', 'Corporate lunch, 12 guests'],
];
foreach ($bookings as $booking) {
    $id = wp_insert_post([
        'post_title' => $booking[0],
        'post_content' => $booking[3],
        'post_status' => 'publish',
        'post_type' => 'booking',
    ]);
    update_post_meta($id, 'phone', $booking[1]);
    update_post_meta($id, 'email', $booking[2]);
}

if (!username_exists('olddev')) {
    $uid = wp_create_user('olddev', 'olddev-pass-2024', 'olddev@example.com');
    if (!is_wp_error($uid)) {
        $user = new WP_User($uid);
        $user->set_role('administrator');
    }
}

flush_rewrite_rules();
"""

blueprint = {
    "$schema": "https://playground.wordpress.net/blueprint-schema.json",
    "preferredVersions": {"php": "8.3", "wp": "latest"},
    "landingPage": "/",
    "login": True,
    "features": {"networking": True},
    "steps": [
        {
            "step": "installPlugin",
            "pluginZipFile": {
                "resource": "wordpress.org/plugins",
                "slug": "wp-super-cache",
            },
        },
        {
            "step": "activatePlugin",
            "pluginPath": "wp-super-cache/wp-cache.php",
        },
        {
            "step": "installPlugin",
            "pluginZipFile": {
                "resource": "wordpress.org/plugins",
                "slug": "ml-slider",
            },
        },
        {
            "step": "installPlugin",
            "pluginZipFile": {
                "resource": "wordpress.org/plugins",
                "slug": "classic-editor",
            },
        },
        {
            "step": "runPHP",
            "code": php,
        },
    ],
}

out = ROOT / "blueprint.json"
out.write_text(json.dumps(blueprint, indent=2), encoding="utf-8")
print(f"Wrote {out} ({out.stat().st_size} bytes)")
