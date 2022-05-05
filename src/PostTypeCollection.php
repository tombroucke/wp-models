<?php

namespace Otomaties\WpModels;

use Traversable;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Collection of PostType objects
 */
class PostTypeCollection implements IteratorAggregate, Countable
{
    /**
     * Array to hold items
     *
     * @var array
     */
    protected array $items = [];
    
    /**
     * Add Post to items
     *
     * @param Post $post
     * @return PostTypeCollection
     */
    public function add(PostType $post) : PostTypeCollection
    {
        $this->items[] = $post;
        return $this;
    }
    
    /**
     * Get iterator
     *
     * @return Traversable
     */
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get first post in collection
     *
     * @return Post|null
     */
    public function first() : ?PostType
    {
        return !empty($this->items) ? $this->items[0] : null;
    }

    /**
     * Get last post in collection
     *
     * @return Post
     */
    public function last() : PostType
    {
        return end($this->items);
    }

    /**
     * Count items in collection
     *
     * @return integer
     */
    public function count() : int
    {
        return count($this->items);
    }
}
