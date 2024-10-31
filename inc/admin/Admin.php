<?php
namespace Tarikul\PersonsStore\Inc\Admin;

use Tarikul\PersonsStore\Inc\Admin\Class\Menu\MenuManager;
use Tarikul\PersonsStore\Inc\Admin\Class\Profile\ProfileManagement;
use Tarikul\PersonsStore\Inc\Admin\Class\Review\ReviewManagement;
use Tarikul\PersonsStore\Inc\Admin\Class\Settings\Settings;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Class\WC_TJMK_Email;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;
use Tarikul\PersonsStore\Inc\AjaxHandler\AjaxHandler;

//use Tarikul\ReviewStore\Inc\AjaxHandler\AjaxHandler;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class Admin
{

    private $plugin_name;
    private $version;
    private $plugin_text_domain;
    private $db;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        global $wpdb;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        $this->db = Database::getInstance();

        add_action('init', [$this, 'init']);

    }

    public function init()
    {
        // Initialize AJAX handling
        new AjaxHandler();

        // Initialize MenuManager 
        new MenuManager($this->plugin_name, $this->version, $this->plugin_text_domain);

        // Initialize ProfileManagement 
        new ProfileManagement($this->plugin_name, $this->version, $this->plugin_text_domain);

        // Initialize ReviewManagement 
        new ReviewManagement($this->plugin_name, $this->version, $this->plugin_text_domain);

        // Initialize ReviewManagement 
        //   new Settings($this->plugin_name, $this->version, $this->plugin_text_domain);

        //   add_filter('woocommerce_email_classes', [$this, 'register_wc_tjmk_email_class']);
    }

    public function enqueue_styles()
    {
        // Get the current screen object
        $screen = get_current_screen();

        // error_log('Current screen ID: ' . $screen->id);
        // Define the allowed page slugs
        $allowed_pages = [
            'toplevel_page_persons-store',
            'tjmk_page_persons-store-pending-profiles',
            'tjmk_page_persons-store-add-person',
            'tjmk_page_persons-store-approve-reviews',
            'tjmk_page_persons-store-pending-review',
            'tjmk_page_persons-store-view-reviews',
            'tjmk_page_persons-store-bulk-upload',
        ];



        // Check if the current page is in the allowed pages
        if (isset($screen->id) && in_array($screen->id, $allowed_pages)) {
            // Enqueue the stylesheet only for the allowed pages
            wp_enqueue_style('tjmk-admin-css', PLUGIN_ADMIN_URL . 'css/tjmk-admin.css', array(), $this->version, 'all');
        }

        // Check if the current page is in the allowed pages
        if (isset($screen->id) && $screen->id === 'tjmk_page_persons-store-add-person') {
            // Enqueue the stylesheet only for the allowed pages
            wp_enqueue_style('tjmk-admin-form-css', PLUGIN_ADMIN_URL . 'css/tjmk-admin-form.css', array(), $this->version, 'all');
        }


    }


    public function enqueue_scripts()
    {
        // Get the current screen object
        $screen = get_current_screen();

        // error_log('Current screen ID: ' . $screen->id);
        // Define the allowed page slugs
        $allowed_pages = [
            'toplevel_page_persons-store',
            'tjmk_page_persons-store-pending-profiles',
            'tjmk_page_persons-store-add-person',
            'tjmk_page_persons-store-approve-reviews',
            'tjmk_page_persons-store-pending-review',
            'tjmk_page_persons-store-view-reviews',
            'tjmk_page_persons-store-bulk-upload',
        ];

        // Check if the current page is in the allowed pages
        if (isset($screen->id) && in_array($screen->id, $allowed_pages)) {

            wp_enqueue_script('tjmk-admin-js', plugin_dir_url(__FILE__) . 'js/tjmk-admin.js', array('jquery'), $this->version, true);
            // Localize script with AJAX data
            wp_localize_script('tjmk-admin-js', 'myPluginAjax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('tjmk_approve_reject_review_nonce'),
                'import_nonce' => wp_create_nonce('urp_import_nonce'), // Add this line for import actions
                'bulk_delete_nonce' => wp_create_nonce('bulk_delete_nonce') // Add this line for import actions
            ]);
        }
    }
}


/**
 * protected $user_management;
    protected $review_management;
    protected $bulk_upload;
    protected $assets_manager;
    protected $menu_manager;

    $this->user_management = new UserManagement();
    $this->review_management = new ReviewManagement();
    $this->bulk_upload = new BulkUpload();
    $this->assets_manager = new AssetsManager();
    $this->menu_manager = new MenuManager();
    
    // Register hooks, etc.
    add_action('admin_menu', [$this->menu_manager, 'register_menu']);
    add_action('admin_enqueue_scripts', [$this->assets_manager, 'enqueue_styles']);
    add_action('admin_enqueue_scripts', [$this->assets_manager, 'enqueue_scripts']);

    Review Manager 
    Menu Manager 
    Admin Assets Manager 
    Profile Manager 
    

 */