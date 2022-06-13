<?php declare(strict_types=1);

use Otomaties\WpModels\User;
use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\UserMeta;
use Otomaties\WpModels\UserCollection;

final class UserTest extends TestCase
{

    protected static $customer;

    public static function setUpBeforeClass() : void
    {
        self::$customer = new Customer(420);
    }

    public function testCanBeCreatedFromId() : void
    {
        $this->assertInstanceOf(
            Customer::class,
            new Customer(420)
        );
    }

    public function testCanBeCreatedFromWpUser() : void
    {
        $customer = new WP_User();
        $this->assertInstanceOf(
            Customer::class,
            new Customer($customer)
        );
    }

    public function testIfIdIsCorrect() : void
    {
        $this->assertEquals(self::$customer->getId(), 420);
    }

    public function testIfMetaIsInstanceOfUserMeta() : void
    {
        $this->assertInstanceOf(UserMeta::class, self::$customer->meta());
    }

    public function testIfRoleIsCustomer() : void
    {
        $this->assertEquals(self::$customer::role(), 'customer');
    }

    public function testIfFindIsUserCollection() : void
    {
        $this->assertInstanceOf(UserCollection::class, self::$customer::find());
    }

    public function testIfInsertReturnsUser() : void
    {
        $this->assertInstanceOf(User::class, self::$customer::insert([]));
        $this->assertInstanceOf(User::class, self::$customer::insert([]));
    }

    public function testIfUpdateReturnsUser() : void
    {
        $this->assertInstanceOf(User::class, self::$customer::update(self::$customer, []));
        $this->assertInstanceOf(User::class, self::$customer::update(self::$customer, []));
    }

    public function testIfDeleteReturnsUser() : void
    {
        $this->assertTrue(self::$customer::delete(self::$customer, []));
    }
}
