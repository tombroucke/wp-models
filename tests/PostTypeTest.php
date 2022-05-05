<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\PostMeta;
use Otomaties\WpModels\PostType;
use Otomaties\WpModels\PostTypeCollection;

final class PostTypeTest extends TestCase
{

    protected static $event;

    public static function setUpBeforeClass() : void
    {
        self::$event = new Event(420);
    }

    public function testCanBeCreatedFromId() : void
    {
        $this->assertInstanceOf(
            PostType::class,
            new Event(420)
        );
    }

    public function testCanBeCreatedFromWpPost() : void
    {
        $post = new WP_Post();
        $this->assertInstanceOf(
            PostType::class,
            new Event($post)
        );
    }

    public function testIfIdIsCorrect() : void
    {
        $this->assertEquals(self::$event->getId(), 420);
    }

    public function testTitleIsCorrect() : void
    {
        $this->assertEquals(self::$event->title(), 'Title of post');
    }

    public function testNameIsCorrect() : void
    {
        $this->assertEquals(self::$event->name(), 'title-of-post');
        $this->assertEquals(self::$event->slug(), 'title-of-post');
    }

    public function testContentIsCorrect() : void
    {
        $this->assertEquals(self::$event->content(), '<p>Post content</p>');
    }

    public function testIfDateIsCorrect() : void
    {
        $this->assertInstanceOf(DateTime::class, self::$event->date());
        $this->assertEquals(self::$event->date()->format('Y-m-d'), '2021-03-24');
    }

    public function testIfUrlIsCorrect() : void
    {
        $this->assertEquals(self::$event->url(), 'https://example.com/title-of-post');
    }

    public function testIfAuthorIsCorrect() : void
    {
        $this->assertEquals(self::$event->author(), 69);
    }

    public function testIfMetaIsInstanceOfPostMeta() : void
    {
        $this->assertInstanceOf(PostMeta::class, self::$event->meta());
    }

    public function testIfPostTypeIsEvent() : void
    {
        $this->assertEquals(self::$event::postType(), 'event');
    }

    public function testIfFindIsPostTypeCollection() : void
    {
        $this->assertInstanceOf(PostTypeCollection::class, self::$event::find());
    }

    public function testIfInsertReturnsPostType() : void
    {
        $this->assertInstanceOf(PostType::class, self::$event::insert([]));
        $this->assertInstanceOf(Event::class, self::$event::insert([]));
    }

    public function testIfUpdateReturnsPostType() : void
    {
        $this->assertInstanceOf(PostType::class, self::$event::update(self::$event, []));
        $this->assertInstanceOf(Event::class, self::$event::update(self::$event, []));
    }

    public function testIfDeleteReturnsPostType() : void
    {
        $this->assertInstanceOf(WP_Post::class, self::$event::delete(self::$event, []));
    }
}
