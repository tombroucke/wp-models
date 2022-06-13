<?php

namespace Otomaties\WpModels;

class UserRepository
{

    /**
     * Define User class name
     *
     * @param string $class
     */
    public function __construct(private string $class)
    {
    }

    /**
     * Insert new user
     *
     * @param array $args
     * @return User
     */
    public function insert(array $args) : User
    {
        $defaults = array(
            'user_login' => null,
            'user_pass' => null,
            'role' => $this->class::role(),
        );

        $args = wp_parse_args($args, $defaults);
        $userId = wp_insert_user($args);

        if (is_wp_error($userId)) {
            throw new \Exception($userId->get_error_code());
        }
        
        return new $this->class($userId);
    }

    /**
     * Update user
     *
     * @param User $post
     * @param array $args
     * @return User
     */
    public function update(User $user, array $args) : User
    {
        $args['ID'] = $user->getId();
        return self::insert($args);
    }

    /**
     * Find posts by ID or query.
     *
     * @param integer|array|null $query Post ID or WP_Query parameters
     * @param integer $number Number of results
     * @param integer $paged User offset
     * @return UserCollection
     */
    public function find(int|array|null $query = null, int $number = -1, int $paged = 1) : UserCollection
    {
        $args = [
            'role' => $this->class::role(),
            'number' => $number,
            'paged' => $paged
        ];

        if (0 === $query) {
            return new UserCollection();
        }

        if (is_int($query)) {
            $args['include'] = [$query];
        } elseif (is_array($query)) {
            $args = wp_parse_args($query, $args);
        }

        $args['fields'] = 'ID';
        $userCollection = new UserCollection();

        foreach (get_users($args) as $user) {
            $userCollection->add(new $this->class($user));
        }
        return $userCollection;
    }

    /**
     * Delete user
     *
     * @param User $user
     * @param boolean $force
     * @return bool
     */
    public function delete(User $user, bool $force = false) : bool
    {
        return wp_delete_user($user->getId(), $force);
    }
}
