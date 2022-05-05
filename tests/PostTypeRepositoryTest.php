<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\PostTypeCollection;
use Otomaties\WpModels\PostTypeRepository;

final class PostTypeRepositoryTest extends TestCase
{
    protected static $postTypeRepository;

    public static function setUpBeforeClass() : void
    {
        self::$postTypeRepository = new PostTypeRepository(Event::class);
    }

    public function testIfObjectCanBeCreatedFromClass() : void
    {
        $this->assertInstanceOf(PostTypeRepository::class, self::$postTypeRepository);
    }

    public function testIfPostTypeCanBeInserted() : void
    {
        $this->assertInstanceOf(Event::class, self::$postTypeRepository->insert([]));
    }

    public function testIfPostTypeCanBeUpdated() : void
    {
        $event = new Event(420);
        $this->assertInstanceOf(Event::class, self::$postTypeRepository->update($event, []));
    }

    public function testIfFindReturnsPostTypeCollection() : void
    {
        $this->assertInstanceOf(PostTypeCollection::class, self::$postTypeRepository->find());
        $this->assertCount(999, self::$postTypeRepository->find());
        $this->assertCount(1, self::$postTypeRepository->find(null, 1));
    }

    public function testIfPostCanBeDeleted() : void
    {
        $event = new Event(420);
        $this->assertInstanceOf(WP_Post::class, self::$postTypeRepository->delete($event));
    }
}
