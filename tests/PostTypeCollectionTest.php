<?php declare(strict_types=1);

use Otomaties\WpModels\PostTypeCollection;
use PHPUnit\Framework\TestCase;

final class PostTypeCollectionTest extends TestCase
{
    public function testIfItemsCanBeAdded() : void
    {
        $collection = new PostTypeCollection();
        $item = new Event(420);
        $this->assertInstanceOf(PostTypeCollection::class, $collection->add($item));
    }

    public function testIfCountIsCorrect() : void
    {
        $collection = new PostTypeCollection();
        $item = new Event(420);
        $collection->add($item);
        $collection->add($item);
        $this->assertCount(2, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testIfFirstItemIsSet() : void
    {
        $collection = new PostTypeCollection();
        $item = new Event(420);
        $collection->add($item);
        $this->assertInstanceOf(Event::class, $collection->first());
    }

    public function testIfLastItemIsSet() : void
    {
        $collection = new PostTypeCollection();
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
        $collection = new PostTypeCollection($events);
        $this->assertInstanceOf(PostTypeCollection::class, $collection);
        $this->assertCount(2, $collection);
    }

    public function testIfCollectionCanBeFiltered() : void
    {
        $events = [
            new Event(420),
            new Event(69)
        ];
        $collection = new PostTypeCollection($events);
        $filteredCollection = $collection->filter(function ($event) {
            return $event->getID() == 420;
        });
        $this->assertCount(1, $filteredCollection);
    }

    public function testIfCollectionCanBeEmpty() : void
    {
        $collection = new PostTypeCollection();
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
        $collection = new PostTypeCollection($events);
        $uniqueCollection = $collection->unique();
        $this->assertCount(1, $uniqueCollection);
    }
}
