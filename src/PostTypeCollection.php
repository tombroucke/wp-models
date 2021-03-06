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
    public function __construct(protected array $items = [])
    {
        $this->items = $items;
    }
    
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

    /**
     * Check if collection is empty
     *
     * @return boolean
     */
    public function empty() : bool
    {
        return empty($this->items);
    }

    /**
     * Filter collection
     *
     * @param callable $filterFunction
     * @return PostTypeCollection
     */
    public function filter(callable $filterFunction) : PostTypeCollection
    {
        $filteredItems = array_filter($this->items, $filterFunction);
        return new PostTypeCollection($filteredItems);
    }

    /**
     * Unique items
     *
     * @return PostTypeCollection
     */
    public function unique() : PostTypeCollection
    {
        $uniqueItems = array_unique($this->items);
        return new PostTypeCollection($uniqueItems);
    }
}
