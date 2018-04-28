<?php

class ProtectContentCustomBox {

    use FTPCSetup;

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method init
     * @return void
     */
    public static function init($plugin) {
        add_action('add_meta_boxes', [$this, 'action_custom_box']);
        add_action('save_post', [$this, 'action_save_postdata']);
        add_action('wp_ajax_ftpc_user_search', [$this, 'ajax_user_search']);
    }

    /**
     * Verify if there's a POST value with the specified name
     * @method has_post_value
     * @param  string         $name Name of the field
     * @return boolean
     */
    protected function has_post_value($name) {
        $name = $this->get_post_field_name($name);
        return array_key_exists($name, $_POST);
    }
    /**
     * Gets the POST value with the specified name
     * @method has_post_value
     * @param  string         $name Name of the fied
     * @param  string         $default Name of the field (optiona, default: null)
     * @return string
     */
    protected function get_post_value($name, $default = null) {
        $name = $this->get_post_field_name($name);
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    /**
     * Get the real name of the POST field
     * @method get_post_field_name
     * @param  string         $name Name of the fied
     * @return string
     */
    public function get_post_field_name($name) {
        return $this->plugin->config['meta']['post-field-base'] . $name;
    }

    /**
     * Wordpress action that saves the meta data
     * @method action_save_postdata
     * @param  integer               $post_id The current post
     * @return void
     */
    public function action_save_postdata($post_id)
    {

        $fields = ['protect', 'redirect_url', 'users'];

        foreach ($fields as $field) {

            if ($this->has_post_value($field)) {

                $value = $this->get_post_value($field);
                $this->plugin->set_option($post_id, $field, $value);

            }

        }

    }

    /**
     * Wordpress action to add a custom box to all posts
     * @method action_custom_box
     * @return void
     */
    function action_custom_box()
    {
        add_meta_box(
            $this->plugin->config['meta']['box-name'],
            'Protect Content',
            [$this, 'custom_box_html'],
            'post'
        );
    }

    /**
     * Callback that displays the custom box
     * @method custom_box_html
     * @param  WP_Post          $post The current post
     * @return void
     */
    public function custom_box_html($post)
    {

        $params = [
            'admin' => $this,
            'protect' => $this->plugin->get_option($post->ID, 'protect'),
            'redirect_url' => $this->plugin->get_option($post->ID, 'redirect_url'),
            'users' => $this->plugin->get_option($post->ID, 'users'),
        ];

        echo($this->plugin->render(__DIR__ . '/../templates/custom-box.php', $params));

    }


    function ajax_user_search() {

        $limit = 10;

        $search = isset($_POST['search']) ? $_POST['search'] : '';
        $page = isset($_POST['page']) ? $_POST['page'] : 1;

        $users = get_users([
            'number' => $limit,
            'orderby' => 'nicename',
            'paged' => $page,
            'search' => $search,
        ]);

        $result = [
            'results' => [],
            'pagination' => [
                'more' => count($users) == $limit,
            ],
        ];

        foreach ($users as $user) {
            $result['results'][] = [
                'id' => $user->id,
                'text' => sprintf('%s %s (%s / #%d)', $user->first_name, $user->last_name, $user->user_login, $user->ID),
            ];
        }

        echo(json_encode($result));

    	wp_die(); // this is required to terminate immediately and return a proper response

    }

}
