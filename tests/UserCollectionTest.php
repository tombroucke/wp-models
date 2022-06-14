<?php declare(strict_types=1);

use Otomaties\WpModels\User;
use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\UserCollection;

final class UserCollectionTest extends TestCase
{
    public function testIfItemsCanBeAdded() : void
    {
        $collection = new UserCollection();
        $item = new Customer(420);
        $this->assertInstanceOf(UserCollection::class, $collection->add($item));
    }

    public function testIfCountIsCorrect() : void
    {
        $collection = new UserCollection();
        $item = new Customer(420);
        $collection->add($item);
        $collection->add($item);
        $this->assertCount(2, $collection);
        $this->assertEquals(2, $collection->count());
    }

    public function testIfFirstItemIsSet() : void
    {
        $collection = new UserCollection();
        $item = new Customer(420);
        $collection->add($item);
        $this->assertInstanceOf(Customer::class, $collection->first());
    }

    public function testIfLastItemIsSet() : void
    {
        $collection = new UserCollection();
        $item = new Customer(420);
        $collection->add($item);
        $this->assertInstanceOf(Customer::class, $collection->last());
    }

    public function testIfCollectionCanBeInitializedFromArray() : void
    {
        $events = [
            new Customer(420),
            new Customer(69)
        ];
        $collection = new UserCollection($events);
        $this->assertInstanceOf(UserCollection::class, $collection);
        $this->assertCount(2, $collection);
    }

    public function testIfCollectionCanBeFiltered() : void
    {
        $events = [
            new Customer(420),
            new Customer(69)
        ];
        $collection = new UserCollection($events);
        $filteredCollection = $collection->filter(function ($event) {
            return $event->getID() == 420;
        });
        $this->assertCount(1, $filteredCollection);
    }

    public function testIfCollectionCanBeEmpty() : void
    {
        $collection = new UserCollection();
        $this->assertTrue($collection->empty());

        $item = new Customer(420);
        $collection->add($item);
        $this->assertFalse($collection->empty());
    }

    public function testIfCollectionUnique() : void
    {
        $events = [
            new Customer(420),
            new Customer(420),
        ];
        $collection = new UserCollection($events);
        $uniqueCollection = $collection->unique();
        $this->assertCount(1, $uniqueCollection);
    }
}
