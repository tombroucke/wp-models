<?php
namespace Otomaties\WpModels;

use Countable;
use Traversable;
use ArrayIterator;
use IteratorAggregate;

class Collection implements IteratorAggregate, Countable
{
    public function __construct(protected array $items = [])
    {
        $this->items = $items;
    }
    
    /**
     * Add Post to items
     *
     * @param $item
     * @return Collection
     */
    public function add($item) : Collection
    {
        $this->items[] = $item;
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
     * @return mixed
     */
    public function first() : mixed
    {
        return !empty($this->items) ? $this->items[0] : null;
    }

    /**
     * Get last post in collection
     *
     * @return mixed
     */
    public function last() : mixed
    {
        return !empty($this->items) ? end($this->items) : null;
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
     * @return Collection
     */
    public function filter(callable $filterFunction) : Collection
    {
        $filteredItems = array_filter($this->items, $filterFunction);
        return new Collection($filteredItems);
    }

    /**
     * Unique items
     *
     * @return Collection
     */
    public function unique() : Collection
    {
        $uniqueItems = array_unique($this->items);
        return new Collection($uniqueItems);
    }

    /**
     * Limit collection
     *
     * @param integer $limit
     * @return Collection
     */
    public function limit(int $limit) : Collection
    {
        $limitedItems = array_slice($this->items, 0, $limit);
        return new Collection($limitedItems);
    }

    /**
     * Paginate collection
     *
     * @param integer $perPage Number of items per page
     * @param integer $page Page number
     * @return Collection
     */
    public function paginate(int $perPage, int $page = 1) : Collection
    {
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($this->items, $offset, $perPage);
        return new Collection($paginatedItems);
    }

    /**
     * Map collection
     *
     * @param callable $mapFunction
     * @return array
     */
    public function map(callable $mapFunction) : array
    {
        return array_map($mapFunction, $this->items);
    }

    /**
     * Order collection by id, title, name & meta key
     *
     * @param string|callable $orderBy - id, title, name or meta key - or function
     * @param string $order - asc or desc
     * @return Collection
     */
    public function orderBy(string|callable $orderBy, string $order = 'desc') : Collection
    {
        $order = strtolower($order);
        $items = $this->items;


        
        if (is_callable($orderBy)) {
            $sortFunction = $orderBy;
        } else {
            $key = strtolower($orderBy);
            switch ($key) {
                case 'id':
                    $sortFunction = function ($a, $b) {
                        return $a->getId() <=> $b->getId();
                    };
                    break;
                case 'title':
                    $sortFunction = function ($a, $b) {
                        return $a->title() <=> $b->title();
                    };
                    break;
                case 'name':
                    $sortFunction = function ($a, $b) {
                        return $a->title() <=> $b->title();
                    };
                    break;
                default:
                    $sortFunction = function ($a, $b) use ($key) {
                        return $a->meta()->get($key) <=> $b->meta()->get($key);
                    };
                    break;
            }
        }

        // date is callable, so we need to fix that
        if ($sortFunction == 'date') {
            $sortFunction = function ($a, $b) {
                return $a->date() <=> $b->date();
            };
        }

        usort($items, $sortFunction);

        if ($order == 'desc') {
            $items = array_reverse($items);
        }

        $this->items = $items;
        return $this;
    }
}
