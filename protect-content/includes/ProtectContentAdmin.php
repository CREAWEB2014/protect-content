<?php

class FTProtectContentAdmin {

    use FTPCSetup;

    // The GET parameter to select a specific page to show
    public static $page = 'ftcp-plugin';

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

        // Only administrators can create plugins
        if (current_user_can('manage_options')) {

            add_action('admin_menu', function() {
                add_management_page('Protect Content Configuration', 'Protect Content', 'manage_options', static::$page, [$this, 'admin_page']);
            });

            add_action('admin_action_ftpc_save_options', [$this, 'action_save_options']);

        }

	}

    /**
     * Render the admin page
     * @method admin_page
     * @return Void
     */
    public function admin_page() {

        $params = [
            'config' => $this->plugin->getConfig(),
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
        ];

        echo($this->plugin->render(__DIR__ . '/../templates/admin.php', $params));

    }

    public function action_save_options() {

        $config = $this->plugin->getConfig();

        $config['default_redirect'] = $this->request('default_redirect', '');

        $this->plugin->setConfig($config);

        wp_redirect(admin_url('admin.php?page=' . static::$page . '&status=saved'));
        exit();

    }

    public function request($name, $default = null, $valid = null) {

        // Find the right request array
        $input = null;
        if (isset($_GET[$name])) {
            $input = $_GET;
        } elseif (isset($_POST[$name])) {
            $input = $_POST;
        }

        // If the value has been found
        if (!is_null($input)) {

            // If the provided value must be an element of $input
            if (is_array($valid)) {

                // If it's a valid value
                if (in_array($input[$name], $valid)) {
                    return $input[$name];
                } else {
                    return $default;
                }

            }

            return $input[$name];

        }

        return $default;

    }

}
