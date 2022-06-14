<?php

namespace Otomaties\WpModels;

use Traversable;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Collection of User objects
 */
class UserCollection implements IteratorAggregate, Countable
{
    public function __construct(protected array $items = [])
    {
        $this->items = $items;
    }
    
    /**
     * Add user to items
     *
     * @param User $user
     * @return UserCollection
     */
    public function add(User $user) : UserCollection
    {
        $this->items[] = $user;
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
     * Get first user in collection
     *
     * @return User|null
     */
    public function first() : ?User
    {
        return !empty($this->items) ? $this->items[0] : null;
    }

    /**
     * Get last user in collection
     *
     * @return User
     */
    public function last() : User
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
     * @return UserCollection
     */
    public function filter(callable $filterFunction) : UserCollection
    {
        $filteredItems = array_filter($this->items, $filterFunction);
        return new UserCollection($filteredItems);
    }

    /**
     * Unique items
     *
     * @return UserCollection
     */
    public function unique() : UserCollection
    {
        $uniqueItems = array_unique($this->items);
        return new UserCollection($uniqueItems);
    }
}
