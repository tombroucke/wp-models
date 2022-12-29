<?php
WP_Mock::bootstrap();

use Otomaties\WpModels\PostType;
use Otomaties\WpModels\User;

class Event extends PostType
{
    public static function postType() : string
    {
        return 'event';
    }
}

class WP_Post
{
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

class WP_User
{
    public $ID = 420;
    public $roles = ['customer'];

    public function __construct(int $id) {
        if (123 == $id) {
            $this->roles = ['author'];
        }
    }
}

class Customer extends User
{
    public static function role() : string
    {
        return 'customer';
    }
}

function get_the_title(int $postId)
{
    $post = new WP_Post();
    return $post->post_title;
}

function get_the_date(string $format, int $postId)
{
    $post = new WP_Post();
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $post->post_date);
    return $date->format($format);
}

function get_post($postId)
{
    return new WP_Post();
}

function get_the_permalink(int $postId)
{
    $post = new WP_Post();
    return 'https://example.com/' . $post->post_name;
}

function get_posts(array $args)
{
    $return = [];
    $args['posts_per_page'] = ( $args['posts_per_page'] == -1 ? 999 : $args['posts_per_page'] );
    for ($i=0; $i < $args['posts_per_page']; $i++) {
        $return[] = new WP_Post();
    }
    return $return;
}

function get_users(array $args)
{
    $return = [];
    $args['number'] = ( $args['number'] == -1 ? 999 : $args['number'] );
    for ($i=0; $i < $args['number']; $i++) {
        $return[] = new WP_User($i);
    }
    return $return;
}

function wp_parse_args(array $args, array $defaults = array())
{
    if (is_object($args)) {
        $parsed_args = get_object_vars($args);
    } elseif (is_array($args)) {
        $parsed_args =& $args;
    } else {
        wp_parse_str($args, $parsed_args);
    }
 
    if (is_array($defaults) && $defaults) {
        return array_merge($defaults, $parsed_args);
    }
    return $parsed_args;
}

function wp_insert_post(array $args, bool $wp_error) : int|\WP_Error
{
    return 5;
}

function wp_insert_user(array $args)
{
    return 5;
}

function wp_delete_post(int $postId)
{
    return new WP_Post();
}

function wp_delete_user(int $postId)
{
    return true;
}

function is_wp_error($thing)
{
    return false;
}

function get_post_meta(int $post_id, string $key = '', bool $single = false)
{
    $post = new WP_Post();
    return $post->meta[$key];
}

function get_post_type(int $postId)
{
    if (987 == $postId) {
        return 'invalid_post_type';
    }
    return 'event';
}

function get_user_by(string $field, int|string $value)
{
    if (987 == $value) {
        return false;
    }
    return new WP_User($value);
}
