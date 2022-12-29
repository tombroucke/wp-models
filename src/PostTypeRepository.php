<?php

namespace Otomaties\WpModels;

class PostTypeRepository
{

    /**
     * Define PostType class name
     *
     * @param string $class
     */
    public function __construct(private string $class)
    {
    }

    /**
     * Insert new post
     *
     * @param array $args
     * @return PostType
     */
    public function insert(array $args) : PostType
    {
        $defaults = [
            'post_type' => $this->class::postType(),
            'post_status' => 'publish',
            'post_title' => '',
            'post_content' => ''
        ];
        $args = wp_parse_args($args, $defaults);
        $postId = wp_insert_post($args, true);
        if (is_wp_error($postId)) {
            throw new \Exception($postId->get_error_code());
        }
        $insertedObject = new $this->class($postId);

        $action = !isset($args['ID']) ? 'inserted' : 'updated';
        $postType = $this->class::postType();
        do_action("wp_models_{$action}_post", $insertedObject, $args);
        do_action("wp_models_{$action}_{$postType}", $insertedObject, $args);

        return $insertedObject;
    }

    /**
     * Update post type
     *
     * @param PostType $postType
     * @param array $args
     * @return PostType
     */
    public function update(PostType $postType, array $args) : PostType
    {
        $args['ID'] = $postType->getId();
        return self::insert($args);
    }

    /**
     * Find posts by ID or query.
     *
     * @param integer|array|null $query Post ID or WP_Query parameters
     * @param integer $limit Number of results
     * @param integer $paged Page offset
     * @return Collection
     */
    public function find(int|array|null $query = null, int $limit = -1, int $paged = 0) : Collection
    {
        $args = [
            'post_type' => $this->class::postType(),
            'posts_per_page' => $limit,
            'paged' => $paged,
            'suppress_filters' => false,
        ];

        if (0 === $query) {
            return new Collection();
        }

        if (is_int($query)) {
            $args['p'] = $query;
        } elseif (is_array($query)) {
            $args = wp_parse_args($query, $args);
        }

        $args['fields'] = 'ids';
        $postCollection = new Collection();

        foreach (get_posts($args) as $post) {
            $postCollection->add(new $this->class($post));
        }
        return $postCollection;
    }

    /**
     * Delete post
     *
     * @param PostType $post
     * @param boolean $force
     * @return \WP_Post|false|null
     */
    public function delete(PostType $post, bool $force = false) : \WP_Post|false|null
    {
        $postType = $this->class::postType();
        $deleted = wp_delete_post($post->getId(), $force);

        if ($deleted) {
            do_action("wp_models_deleted_post", $post, $force);
            do_action("wp_models_deleted_{$postType}", $post, $force);
        }

        return $deleted;
    }

    /**
     * Trash post
     *
     * @param PostType $post
     * @return \WP_Post|false|null
     */
    public function trash(PostType $post) : \WP_Post|false|null
    {
        $postType = $this->class::postType();
        $trashed = wp_trash_post($post->getId());

        if ($trashed) {
            do_action("wp_models_trashed_post", $post);
            do_action("wp_models_trashed_{$postType}", $post);
        }

        return $trashed;
    }
}
