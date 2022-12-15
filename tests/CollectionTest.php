<?php declare(strict_types=1);

use Otomaties\WpModels\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    public function testIfItemsCanBeAdded() : void
    {
        $collection = new Collection();
        $item = new Event(420);
        $this->assertInstanceOf(Collection::class, $collection->add($item));
    }

    public function testIfCountIsCorrect() : void
    {
        $collection = new Collection();
        $item = new Event(420);
        $collection->add($item);
        $collection->add($item);
        $this->assertCount(2, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testIfFirstItemIsSet() : void
    {
        $collection = new Collection();
        $item = new Event(420);
        $collection->add($item);
        $this->assertInstanceOf(Event::class, $collection->first());
    }

    public function testIfLastItemIsSet() : void
    {
        $collection = new Collection();
        $item = new Event(420);
        $collection->add($item);
        $this->assertInstanceOf(Event::class, $collection->last());
    }

    public function testIfCollectionCanBeInitializedFromArray() : void
    {
        $events = [
            new Event(420),
            new Event(69)
        ];
        $collection = new Collection($events);
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
    }

    public function testIfCollectionCanBeFiltered() : void
    {
        $events = [
            new Event(420),
            new Event(69)
        ];
        $collection = new Collection($events);
        $filteredCollection = $collection->filter(function ($event) {
            return $event->getId() == 420;
        });
        $this->assertCount(1, $filteredCollection);
    }

    public function testIfCollectionCanBeEmpty() : void
    {
        $collection = new Collection();
        $this->assertTrue($collection->empty());

        $item = new Event(420);
        $collection->add($item);
        $this->assertFalse($collection->empty());
    }

    public function testIfCollectionUnique() : void
    {
        $events = [
            new Event(420),
            new Event(420),
        ];
        $collection = new Collection($events);
        $uniqueCollection = $collection->unique();
        $this->assertCount(1, $uniqueCollection);
    }

    public function testCollectionCanBeMapped() : void
    {
        $events = [
            new Event(420),
            new Event(69),
            new Event(42),
        ];

        $collection = new Collection($events);
        $ids = $collection->map(function ($postType) {
            return $postType->getId();
        });
        $this->assertEquals([420, 69, 42], $ids);
    }

    public function testIfCollectionCanBeOrdered() : void
    {
        $events = [
            new Event(420),
            new Event(69),
            new Event(42),
        ];

        $events[0]->post_title = 'bac';
        $events[1]->post_title = 'abc';
        $events[2]->post_title = 'cab';


        $collection = new Collection($events);
        $orderedCollection = $collection->orderBy('ID', 'DESC');
        $this->assertEquals(420, $orderedCollection->first()->getId());

        $orderedCollection = $collection->orderBy('ID', 'ASC');
        $this->assertEquals(42, $orderedCollection->first()->getId());

        $orderedCollection = $collection->orderBy(function ($a, $b) {
            return $a->getId() <=> $b->getId();
        }, 'ASC');
        $this->assertEquals(420, $orderedCollection->last()->getId());

        $orderedCollection = $collection->orderBy(function ($a, $b) {
            return $a->getId() <=> $b->getId();
        }, 'DESC');
        $this->assertEquals(420, $orderedCollection->first()->getId());
    }

    public function testIfCollectionCanBeLimited() : void
    {
        $events = [
            new Event(420),
            new Event(69),
            new Event(42),
        ];

        $collection = new Collection($events);
        $limitedCollection = $collection->limit(2);
        $this->assertCount(2, $limitedCollection);
        $this->assertEquals(new Collection(array_splice($events, 0, 2)), $limitedCollection);
    }

    public function testIfCollectionCanBePaginated() : void
    {
        $events = [
            new Event(420),
            new Event(69),
            new Event(42),
        ];

        $perPage = 2;
        $collection = new Collection($events);

        $firstPage = $collection->paginate($perPage, 1);
        $this->assertCount($perPage, $firstPage);

        $secondPage = $collection->paginate($perPage, 2);
        $this->assertCount(1, $secondPage);
        $this->assertEquals(new Collection(array_splice($events, $perPage, 1)), $secondPage);
        
        $thirdPage = $collection->paginate($perPage, 3);
        $this->assertCount(0, $thirdPage);
    }
}
