<?php declare(strict_types=1);

use Otomaties\WpModels\User;
use PHPUnit\Framework\TestCase;
use Otomaties\WpModels\UserMeta;
use Otomaties\WpModels\Collection;
use Otomaties\WpModels\Exceptions\InvalidUserException;
use Otomaties\WpModels\Exceptions\InvalidUserRoleException;

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
        $customer = new WP_User(420);
        $this->assertInstanceOf(
            Customer::class,
            new Customer($customer)
        );
    }

    public function testIfInvalidUserExceptionIsThrown() : void
    {
        $this->expectException(InvalidUserException::class);
        new Customer(987);
    }

    public function testIfInvalidUserRoleExceptionIsThrown() : void
    {
        $this->expectException(InvalidUserRoleException::class);
        $customer = new Customer(123);
    }

    public function testIfIdIsCorrect() : void
    {
        $this->assertEquals(self::$customer->getId(), 420);
    }

    public function testIfToStringReturnsId() : void
    {
        $this->assertEquals((string)self::$customer, 420);
    }

    public function testIfMetaIsInstanceOfUserMeta() : void
    {
        $this->assertInstanceOf(UserMeta::class, self::$customer->meta());
    }

    public function testIfRoleIsCustomer() : void
    {
        $this->assertEquals(self::$customer::role(), 'customer');
    }

    public function testIfFindIsCollection() : void
    {
        $this->assertInstanceOf(Collection::class, self::$customer::find());
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
