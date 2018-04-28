<?php

class FTProtectContent {

    // The name of the option for the plugin
    protected static $option = 'ftpc_plugin_config';

    // The instance of the class (to avoid global variables)
    protected static $instance = NULL;

    // Configuration variable
    public $config = [];

    /**
     * The static method that initialize the plugin
     * @method start
     */
    static public function start() {
        add_action('init', [self::instance(), 'init']);
    }

    /**
     * The static method that returns the current instance of the plugin
     * @method instance
     */
    static public function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Activation hook
     * @method activate
     * @return void
     */
    public function activate() {

    }

    /**
     * Deactivation hook
     * @method deactivate
     * @return void
     */
    public function deactivate() {

    }

    /**
     * Public constructor
     * @method __construct
     */
     public function __construct() {

        $this->config = $this->getConfig();
        FTProtectContentAdmin::start($this);
        FTProtectContentCustomBox::start($this);
        FTProtectContentAPI::start($this);

    }

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_scripts']);
        add_action('template_redirect', [$this, 'action_template_redirect'], -1000);
	}

    public function action_template_redirect() {

        global $post;

    	if (is_singular() && !empty($post)) {

            $meta = $this->get_meta($post);

            if ($meta['protect']) {

                if (!is_user_logged_in()) {

                    wp_redirect(wp_login_url(get_permalink()));
                    exit;

                }

                if (!in_array(get_current_user_id(), $meta['users'])) {

                    wp_redirect($meta['redirect_url']);
                    exit;

                }

            }

        }

    }

    /**
     * Action for wp_enqueue_scripts
     * @method action_enqueue_scripts
     * @return void
     */
     public function action_enqueue_scripts($hook) {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style ('wp-jquery-ui-dialog');

        wp_enqueue_script('ftpc-custom-js', plugins_url('assets/js/select2.min.js', __DIR__));
        wp_enqueue_style('ftpc-custom-css', plugins_url('assets/css/select2.min.css', __DIR__));

     }

    /**
     * Normalize the configuration for safe use
     * @method normalizeConfig
     * @param  Array    $config An associative array of settings
     * @return Array              The normalized settings
     */
    protected function normalizeConfig($config) {

        $default = include(__DIR__ . '/../config.php');

        $config = is_array($config) ? $config : [];

        $config['meta'] = $default['meta'];

        $config['default_redirect'] = isset($config['default_redirect']) ? $config['default_redirect'] : $default['default_redirect'];

        return $config;
    }

    /**
     * Get the plugin settings
     * @method getConfig
     * @return Array       The plugin settings
     */
    public function getConfig() {
        return $this->normalizeConfig(get_option(self::$option));
    }

    /**
     * Set the plugin configuration
     * @method setConfig
     * @param  Array       $config The settings to be saved
     */
    public function setConfig($config) {
        update_option(self::$option, $this->normalizeConfig($config));
    }

    /**
     * Render the specified template
     * @method render
     * @param  String  $template The path of the template to be rendered
     * @param  array   $params   An associative array of variables to be used in the template
     * @return String            The rendered template
     */
    public function render() {

        // Using func_num_args() and func_get_arg() to avoid
        // injectiong external variables in the shortcode inclusion.

        if (func_num_args() == 0) {
            throw new Exception(sprintf('Missing parameters to %s::%s', __CLASS__, __METHOD__));
        }

        ob_start();

        if (func_num_args() >= 2) {
            extract(func_get_arg(1));
        }

        include(func_get_arg(0));

        return ob_get_clean();

    }

    /**
     * Deletes a post meta
     * @method delete_option
     * @param  integer       $post_id The post id
     * @param  string         $name Name of the meta
     * @return void
     */
    public function delete_option($post_id, $name) {
        $meta_name = $this->get_meta_name($name);
        delete_post_meta($post_id, $meta_name);
    }

    /**
     * Get a post meta value
     * @method get_option
     * @param  integer       $post_id The post id
     * @param  string         $name Name of the meta
     * @return mixed
     */
    public function get_option($post_id, $name) {

        $meta_name = $this->get_meta_name($name);

        $fields = ['protect', 'redirect_url', 'users'];
        if (in_array($name, $fields)) {
            return get_post_meta($post_id, $meta_name, TRUE);
        }

        return FALSE;

    }

    /**
     * Set a post meta value
     * @method get_option
     * @param  integer       $post_id The post id
     * @param  string         $name Name of the meta
     * @param  mixed         $value The value of the meta
     * @return mixed
     */
    public function set_option($post_id, $name, $value) {

        $meta_name = $this->get_meta_name($name);

        $fields = ['protect', 'redirect_url', 'users'];
        if (in_array($name, $fields)) {
            return update_post_meta($post_id, $meta_name, $value);
        }

        return FALSE;

    }

    /**
     * Get the name of a meta field
     * @method get_meta_name
     * @param  string        $name The name of the meta
     * @return string
     */
    public function get_meta_name($name) {
        return $this->config['meta']['meta-base'] . $name;
    }

    /**
     * Get all the calendar meta fields form a specific post
     * @method get_meta
     * @param  WP_Post   $post The current post
     * @return array
     */
    public function get_meta($post) {

        $result = [
            'protect' => $this->get_option($post->ID, 'protect'),
            'redirect_url' => $this->get_option($post->ID, 'redirect_url'),
            'users' => $this->get_option($post->ID, 'users'),
        ];

        $result['redirect_url'] = $result['redirect_url'] == '' ? $this->config['default_redirect'] : $result['redirect_url'];

        return $result;

    }



}
