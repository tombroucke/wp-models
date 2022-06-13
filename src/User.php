<?php

namespace Otomaties\WpModels;

abstract class User
{
    /**
     * Post ID
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
     * Initialize User
     *
     * @param integer|\userId $userId
     */
    public function __construct(int|\WP_User $userId)
    {
        if ($userId instanceof \WP_User) {
            $userId = $userId->ID;
        }
    
        $this->id = $userId;
        $this->meta = new UserMeta($this);
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
            return "${firstName} ${lastName}";
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
     * @return UserCollection
     */
    public static function find(int|array|null $query = null, int $number = -1, int $paged = 1) : UserCollection
    {
        $repository = new UserRepository(static::class);
        return $repository->find($query, $number, $paged);
    }

    /**
     * Insert new post of this type
     *
     * @param array $args
     * @return User
     */
    public static function insert(array $args) : User
    {
        $repository = new UserRepository(static::class);
        return $repository->insert($args);
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
        $repository = new UserRepository(static::class);
        return $repository->update($user, $args);
    }

    /**
     * Delete post
     *
     * @param User $user
     * @return boolean
     */
    public static function delete(User $user) : bool
    {
        $repository = new UserRepository(static::class);
        return $repository->delete($user);
    }
}