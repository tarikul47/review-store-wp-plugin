<?php
namespace Tarikul\ReviewStore\Inc\Admin;

use Tarikul\ReviewStore\Inc\Database\Database;

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
        add_action('admin_menu', array($this, 'urs_admin_menu'));
        $this->db = Database::getInstance($wpdb);

        // Register form submission handler
        //   add_action('admin_post_handle_add_user_form', array($this, 'handle_add_user_form_submission'));

        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }

    public function urs_admin_menu()
    {
        add_menu_page(
            __('Review Store', $this->plugin_text_domain),
            __('Review Store', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name,
            array($this, 'urs_user_list_page'),
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Edit User', $this->plugin_text_domain),
            __('', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-edit-user',
            array($this, 'urs_edit_user_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Add User', $this->plugin_text_domain),
            __('Add User', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-add-user',
            array($this, 'urs_add_user_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Approve Reviews', $this->plugin_text_domain),
            __('Approve Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-approve-reviews',
            array($this, 'urs_approve_reviews_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Review', $this->plugin_text_domain),
            __('Pending Review', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-pending-review',
            array($this, 'urs_pending_reviews_page')
        );
    }

    public function urs_user_list_page()
    {
        // if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['user_id'])) {
        //     $this->handle_delete_user(intval($_GET['user_id']));
        // }

        // if (isset($_POST['bulk_delete_users']) && !empty($_POST['user_ids'])) {
        //     $this->handle_bulk_delete_users(array_map('intval', $_POST['user_ids']));
        // }

        $users = $this->db->get_users_with_review_data();

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-user-list-display.php';
    }

    public function urs_edit_user_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-edit-user-display.php';
    }

    public function urs_add_user_page()
    {

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-add-user-display.php';
    }

    public function urs_approve_reviews_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-approve-reviews-display.php';
    }

    public function urs_pending_reviews_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-pending-reviews-display.php';
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/review-store-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/review-store-admin.js', array('jquery'), $this->version, false);
    }
}