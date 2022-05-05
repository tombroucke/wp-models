<?php

namespace Otomaties\WpModels;

/**
 * Interact with post meta fields
 */
class PostMeta
{
    /**
     * Create post meta for certain post
     *
     * @param PostType $post
     */
    public function __construct(private PostType $post)
    {
    }

    /**
     * Get post meta
     *
     * @param string|null $key
     * @param boolean $single
     * @return mixed
     */
    public function get(string $key = null, bool $single = true) : mixed
    {
        return get_post_meta($this->post->getId(), $key, $single);
    }

    /**
     * Set post meta
     *
     * @param string $key
     * @param mixed $value
     * @return PostMeta
     */
    public function set(string $key, mixed $value) : PostMeta
    {
        update_post_meta($this->post->getId(), $key, $value);
        return $this;
    }

    /**
     * Add post meta
     *
     * @param string $key
     * @param mixed $value
     * @return PostMeta
     */
    public function add(string $key, mixed $value) : PostMeta
    {
        add_post_meta($this->post->getId(), $key, $value);
        return $this;
    }

    /**
     * Remove post meta
     *
     * @param string $key
     * @param mixed $value
     * @return PostMeta
     */
    public function remove(string $key, mixed $value = null) : PostMeta
    {
        delete_post_meta($this->post->getId(), $key, $value);
        return $this;
    }
}
