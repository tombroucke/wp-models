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

    public function testIfFirstItemIsSet() {
        $collection = new PostTypeCollection();
        $item = new Event(420);
        $collection->add($item);
        $this->assertInstanceOf(Event::class, $collection->first());
    }

    public function testIfLastItemIsSet() {
        $collection = new PostTypeCollection();
        $item = new Event(420);
        $collection->add($item);
        $this->assertInstanceOf(Event::class, $collection->last());
    }
}
