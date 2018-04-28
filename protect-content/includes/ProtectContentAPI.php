<?php

class FTProtectContentAPI {

    use FTPCSetup;

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

	}

    /**
     * Add an user to a protected content
     * @method add_user
     * @param  [type]   $post [description]
     * @param  [type]   $user [description]
     */
    public static function add_user($post, $user) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;
        $user_id = is_a($user, WP_User::class) ? $user->ID : (int) $user;

        $current = $api->plugin->get_option($post_id, 'users');
        $current = array_unique(array_merge($current, [$user_id]));

        $api->plugin->set_option($post_id, 'users', $current);

        return true;

    }

    /**
     * Remove an user from a protected content
     * @method remove_user
     * @param  [type]      $post [description]
     * @param  [type]      $user [description]
     * @return [type]            [description]
     */
    public static function remove_user($post, $user) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;
        $user_id = is_a($user, WP_User::class) ? $user->ID : (int) $user;

        $current = $api->plugin->get_option($post_id, 'users');
        $current = array_unique(array_diff($current, [$user_id]));

        $api->plugin->set_option($post_id, 'users', $current);

        return true;

    }

    /**
     * Add multiple users from a protected content
     * @method add_users
     * @param  [type]    $post  [description]
     * @param  [type]    $users [description]
     */
    public static function add_users($post, $users) {

        if (!is_array($users)) {
            return false;
        }

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        $current = $api->plugin->get_option($post_id, 'users');

        foreach ($users as $user) {
            $current[] = is_a($user, WP_User::class) ? $user->ID : (int) $user;
        }

        $current = array_unique($current);

        $api->plugin->set_option($post_id, 'users', $current);

    }

    /**
     * Remove multiple users from a protected content
     * @method remove_users
     * @param  [type]       $post  [description]
     * @param  [type]       $users [description]
     * @return [type]              [description]
     */
    public static function remove_users($post, $users) {

        if (!is_array($users)) {
            return false;
        }

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        $current = $api->plugin->get_option($post_id, 'users');

        $exclude = [];
        foreach ($users as $user) {
            $exclude[] = is_a($user, WP_User::class) ? $user->ID : (int) $user;
        }
        $current = array_unique(array_diff($current, $exclude));

        $api->plugin->set_option($post_id, 'users', $current);

        return true;

    }

    /**
     * Protecte a content
     * @method protect_content
     * @param  [type]          $post [description]
     * @return [type]                [description]
     */
    public static function protect_content($post) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        $api->plugin->set_option($post_id, 'protect_content', 1);

        return true;

    }

    /**
     * Unprotect a content
     * @method unprotect_content
     * @param  [type]            $post [description]
     * @return [type]                  [description]
     */
    public static function unprotect_content($post) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        $api->plugin->set_option($post_id, 'protect_content', 0);

        return true;

    }

    /**
     * Set content redirect URL
     * @method set_redirect_url
     * @param  [type]           $post         [description]
     * @param  [type]           $redirect_url [description]
     */
    public static function set_redirect_url($post, $redirect_url) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        $api->plugin->set_option($post_id, 'redirect_url', $redirect_url);

        return true;

    }

    /**
     * Get a post protection details
     * @method get_protection_details
     * @param  [type]                 $post [description]
     * @return [type]                       [description]
     */
    public static function get_protection_details($post) {

        $api = static::instance();

        $post_id = is_a($post, WP_Post::class) ? $post->ID : (int) $post;

        return $api->plugin->get_meta($post_id);

    }

}
