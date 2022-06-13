<?php

namespace Otomaties\WpModels;

/**
 * Interact with user meta fields
 */
class UserMeta
{
    /**
     * Create user meta for certain user
     *
     * @param User $user
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Get user meta
     *
     * @param string|null $key
     * @param boolean $single
     * @return mixed
     */
    public function get(string $key = null, bool $single = true) : mixed
    {
        return get_user_meta($this->user->getId(), $key, $single);
    }

    /**
     * Set user meta
     *
     * @param string $key
     * @param mixed $value
     * @return UserMeta
     */
    public function set(string $key, mixed $value) : UserMeta
    {
        update_user_meta($this->user->getId(), $key, $value);
        return $this;
    }

    /**
     * Add user meta
     *
     * @param string $key
     * @param mixed $value
     * @return UserMeta
     */
    public function add(string $key, mixed $value) : UserMeta
    {
        add_user_meta($this->user->getId(), $key, $value);
        return $this;
    }

    /**
     * Remove user meta
     *
     * @param string $key
     * @param mixed $value
     * @return UserMeta
     */
    public function remove(string $key, mixed $value = null) : UserMeta
    {
        delete_user_meta($this->user->getId(), $key, $value);
        return $this;
    }
}
