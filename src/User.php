<?php

namespace Otomaties\WpModels;

use Otomaties\WpModels\Exceptions\InvalidUserException;
use Otomaties\WpModels\Exceptions\InvalidUserRoleException;

abstract class User extends Model
{
    /**
     * User ID
     *
     * @var integer
     */
    protected int $id;

    /**
     * UserMeta object to interact with user meta
     *
     * @var UserMeta
     */
    private UserMeta $meta;

    /**
     * WordPress user object
     *
     * @var \WP_User
     */
    protected \WP_User $wpUser;

    /**
     * Initialize User
     *
     * @param integer|\WP_User $userId
     */
    public function __construct(int|\WP_User $userId)
    {
        if ($userId instanceof \WP_User) {
            $userId = $userId->ID;
        }
    
        $this->id = $userId;
        $this->meta = new UserMeta($this);

        if (!(bool) get_user_by('id', $userId)) {
            throw new InvalidUserException(
                sprintf(
                    'User with ID %s doesn\'t exist.',
                    $userId
                ),
                1
            );
        }

        $this->wpUser = new \WP_User($userId);

        $roles = explode(',', $this::role());
        if (count(array_intersect($roles, $this->wpUser->roles)) === 0) {
            throw new InvalidUserRoleException(
                sprintf(
                    'User with ID %s doesn\'t have the %s role.',
                    $userId,
                    $this::role()
                ),
                1
            );
        }
    }

    /**
     * Get user ID
     *
     * @return integer
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get UserMeta object
     *
     * @return UserMeta
     */
    public function meta() : UserMeta
    {
        return $this->meta;
    }

    /**
     * Get user first name
     *
     * @return string
     */
    public function firstName() : string
    {
        return $this->meta()->get('first_name');
    }

    /**
     * Get user last name
     *
     * @return string
     */
    public function lastName() : string
    {
        return $this->meta()->get('last_name');
    }

    /**
     * Get user full name
     *
     * @return string
     */
    public function name() : string
    {
        $firstName = $this->firstName();
        $lastName = $this->lastName();

        if ($firstName) {
            return "{$firstName} {$lastName}";
        }

        return $this->displayName();
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function email() : string
    {
        return $this->userdata('user_email');
    }

    /**
     * Get user login
     *
     * @return string
     */
    public function login() : string
    {
        return $this->userdata('user_login');
    }

    /**
     * Get user url
     *
     * @return string|null
     */
    public function userUrl() : ?string
    {
        return $this->userdata('user_url');
    }

    /**
     * Get user display name
     *
     * @return string|null
     */
    public function displayName() : ?string
    {
        return $this->userdata('display_name');
    }

    /**
     * Get user data by key
     *
     * @param string $key
     * @return string|null
     */
    public function userdata(string $key) : ?string
    {
        $data = get_userdata($this->getId());
        return $data->$key;
    }

    /**
     * Get user role
     *
     * @return string
     */
    abstract public static function role() : string;

    /**
     * Find post of this type
     *
     * @param integer|array|null|null $query
     * @param integer $number
     * @param integer $paged
     * @return Collection
     */
    public static function find(int|array|null $query = null, int $number = -1, int $paged = 1) : Collection
    {
        return (new UserRepository(static::class))->find($query, $number, $paged);
    }

    /**
     * Insert new post of this type
     *
     * @param array $args
     * @return User
     */
    public static function insert(array $args) : User
    {
        return (new UserRepository(static::class))->insert($args);
    }

    /**
     * Update post of this type
     *
     * @param User $user
     * @param array $args
     * @return User
     */
    public static function update(User $user, array $args) : User
    {
        return (new UserRepository(static::class))->update($user, $args);
    }

    /**
     * Delete post
     *
     * @param User $user
     * @return boolean
     */
    public static function delete(User $user) : bool
    {
        return (new UserRepository(static::class))->delete($user);
    }
    
    /**
     * Return ID when converting to string
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->getId();
    }
}
