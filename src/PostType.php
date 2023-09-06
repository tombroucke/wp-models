<?php

namespace Otomaties\WpModels;

use DateTime;
use Otomaties\WpModels\Exceptions\InvalidPostTypeException;

abstract class PostType extends Model
{
    /**
     * Post ID
     *
     * @var integer
     */
    protected int $id;

    /**
     * PostMeta object to interact with post meta
     *
     * @var PostMeta
     */
    private PostMeta $meta;

    /**
     * Initialize Post Type
     *
     * @param integer|\WP_Post $postId
     */
    public function __construct(int|\WP_Post $postId)
    {
        if ($postId instanceof \WP_Post) {
            $postId = $postId->ID;
        }
    
        $this->id = $postId;
        $this->meta = new PostMeta($this);
        
        if ($this::postType() != get_post_type($postId)) {
            throw new InvalidPostTypeException(
                sprintf(
                    'Invalid post type. Expected %s, got %s.',
                    $this::postType(),
                    get_post_type($postId) ?: 'undefined'
                ),
                1
            );
        }
    }

    /**
     * Get ID
     *
     * @return integer
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get post title
     *
     * @return string
     */
    public function title() : string
    {
        return get_the_title($this->getId());
    }
    
    /**
     * Get post name (slug)
     *
     * @return string
     */
    public function name() : string
    {
        $post_object = get_post($this->getId());
        return $post_object->post_name;
    }
    
    /**
     * Get post slug
     *
     * @return string
     */
    public function slug() : string
    {
        return $this->name();
    }

    /**
     * Get post content
     *
     * @return string
     */
    public function content() : string
    {
        $post_object = get_post($this->getId());
        return $post_object->post_content;
    }

    /**
     * Get post date
     *
     * @return DateTime
     */
    public function date() : DateTime
    {
        $date = get_the_date('Ymd', $this->getId());
        return DateTime::createFromFormat('Ymd', $date);
    }

    /**
     * Get post link
     *
     * @return string
     */
    public function url() : string
    {
        return get_the_permalink($this->getId());
    }

    /**
     * Get post author
     *
     * @return integer
     */
    public function author() : int
    {
        $post_object = get_post($this->getId());
        return (int)$post_object->post_author;
    }

    /**
     * Get PostMeta object
     *
     * @return PostMeta
     */
    public function meta() : PostMeta
    {
        return $this->meta;
    }

    /**
     * Define post type
     *
     * @return string
     */
    abstract public static function postType() : string;

    /**
     * Find post of this type
     *
     * @param integer|array|null|null $query
     * @param integer $limit
     * @param integer $paged
     * @return Collection
     */
    public static function find(int|array|null $query = null, int $limit = -1, int $paged = 0) : Collection
    {
        return (new PostTypeRepository(static::class))->find($query, $limit, $paged);
    }

    /**
     * Insert new post of this type
     *
     * @param array $args
     * @return PostType
     */
    public static function insert(array $args) : PostType
    {
        return (new PostTypeRepository(static::class))->insert($args);
    }

    /**
     * Update post of this type
     *
     * @param PostType $postType
     * @param array $args
     * @return PostType
     */
    public static function update(PostType $postType, array $args) : PostType
    {
        return (new PostTypeRepository(static::class))->update($postType, $args);
    }

    /**
     * Trash post
     *
     * @param PostType $postType
     * @return \WP_Post|false|null
     */
    public static function trash(PostType $postType) : \WP_Post|false|null
    {
        return (new PostTypeRepository(static::class))->trash($postType);
    }

    /**
     * Delete post
     *
     * @param PostType $postType
     * @return \WP_Post|false|null
     */
    public static function delete(PostType $postType) : \WP_Post|false|null
    {
        return (new PostTypeRepository(static::class))->delete($postType);
    }
    
    /**
     * Return ID when converting to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->getId();
    }
}
