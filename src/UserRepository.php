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
        $insertedObject = new $this->class($userId);

        $action = !isset($args['ID']) ? 'inserted' : 'updated';
        do_action("wp_models_{$action}_user", $insertedObject, $args);
        do_action("wp_models_{$action}_" . $this->class::role(), $insertedObject, $args);
        
        return $insertedObject;
    }

    /**
     * Update user
     *
     * @param User $user
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
     * @return Collection
     */
    public function find(int|array|null $query = null, int $number = -1, int $paged = 1) : Collection
    {
        $args = [
            'role__in' => explode(',', $this->class::role()),
            'number' => $number,
            'paged' => $paged
        ];

        if (0 === $query) {
            return new Collection();
        }

        if (is_int($query)) {
            $args['include'] = [$query];
        } elseif (is_array($query)) {
            $args = wp_parse_args($query, $args);
        }

        $args['fields'] = 'ID';
        $collection = new Collection();

        foreach (get_users($args) as $user) {
            $collection->add(new $this->class($user));
        }
        return $collection;
    }

    /**
     * Delete user
     *
     * @param User $user
     * @param int|null $reassignToUserId User ID to reassign posts and links to
     * @return bool
     */
    public function delete(User $user, ?int $reassignToUserId = null) : bool
    {
        if (!function_exists('wp_delete_user')) {
            require_once(ABSPATH . 'wp-admin/includes/user.php');
        }

        $role = $this->class::role();
        $userDeleted = wp_delete_user($user->getId(), $reassignToUserId);

        if ($userDeleted) {
            do_action("wp_models_delete_user", $user, $reassignToUserId);
            do_action("wp_models_delete_{$role}", $user, $reassignToUserId);
        }
        
        return $userDeleted;
    }
}
