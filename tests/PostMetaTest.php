<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\PostMeta;

final class PostMetaTest extends TestCase
{
    protected static $postMeta;

    public static function setUpBeforeClass() : void
    {
        $event = new Event(420);
        self::$postMeta = new PostMeta($event);
    }

    public function testIfCanBeConstructedFromPostType() : void
    {
        $this->assertInstanceOf(PostMeta::class, self::$postMeta);
    }

    public function testIfMetaCanBeFoundByKey() : void
    {
        $this->assertEquals('meta_value', self::$postMeta->get('meta_key'));
        $this->assertIsArray(self::$postMeta->get('meta_array_key'));
        $this->assertContains('meta_value', self::$postMeta->get('meta_array_key'));
    }

    public function testIfMetaCanBeSet() : void
    {
        self::$postMeta->set('meta_custom_key', 'meta_custom_value');
    }

    // TODO: Test is incomplete, cannot test update, add & delete post meta without wp functions & database
    // WP unit test is needed
}
