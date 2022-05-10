<?php

use Otomaties\WpModels\PostType;

class Event extends PostType
{
    public static function postType() : string
    {
        return 'event';
    }
}

class WP_Post {
    public $ID = 420;
    public $post_title = 'Title of post';
    public $post_name = 'title-of-post';
    public $post_content = '<p>Post content</p>';
    public $post_date = '2021-03-24 14:15:18';
    public $post_author = 69;
    public $meta = [
        'meta_key' => 'meta_value',
        'meta_array_key' => ['meta_value']
    ];
}

function get_the_title($postId) {
    $post = new WP_Post();
    return $post->post_title;
}

function get_the_date($format, $postId) {
    $post = new WP_Post();
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
    return $date->format($format);
}

function get_post($postId) {
    return new WP_Post();
}

function get_the_permalink($postId) {
    $post = new WP_Post();
    return 'https://example.com/' . $post->post_name;
}

function get_posts($args) {
    $return = [];
    $args['posts_per_page'] = ( $args['posts_per_page'] == -1 ? 999 : $args['posts_per_page'] );
    for ($i=0; $i < $args['posts_per_page']; $i++) { 
        $return[] = new WP_Post();
    }
    return $return;
}

function wp_parse_args( $args, $defaults = array() ) {
    if ( is_object( $args ) ) {
        $parsed_args = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $parsed_args =& $args;
    } else {
        wp_parse_str( $args, $parsed_args );
    }
 
    if ( is_array( $defaults ) && $defaults ) {
        return array_merge( $defaults, $parsed_args );
    }
    return $parsed_args;
}

function wp_insert_post($args) {
    return 5;
}

function wp_delete_post($postId) {
    return new WP_Post();
}

function is_wp_error($thing) {
    return false;
}

function get_post_meta(int $post_id, string $key = '', bool $single = false) {
    $post = new WP_Post();
    return $post->meta[$key];
}