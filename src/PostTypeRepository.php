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
        $postId = wp_insert_post($args);
        if (is_wp_error($postId)) {
            throw new \Exception($postId->get_error_code());
        }
        return new $this->class($postId);
    }

    /**
     * Update post type
     *
     * @param PostType $post
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
     * @return PostTypeCollection
     */
    public function find(int|array|null $query = null, int $limit = -1, int $paged = 0) : PostTypeCollection
    {
        $args = [
            'post_type' => $this->class::postType(),
            'posts_per_page' => $limit,
            'paged' => $paged
        ];

        if (is_int($query)) {
            $args['p'] = $query;
        } elseif (is_array($query)) {
            $args = wp_parse_args($query, $args);
        }

        $args['fields'] = 'ids';
        $postCollection = new PostTypeCollection();

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
        return wp_delete_post($post->getId(), $force);
    }
}
