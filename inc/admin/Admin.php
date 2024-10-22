<?php
namespace Tarikul\PersonsStore\Inc\Admin;

use Tarikul\PersonsStore\Inc\Admin\Class\Menu\MenuManager;
use Tarikul\PersonsStore\Inc\Admin\Class\Profile\ProfileManagement;
use Tarikul\PersonsStore\Inc\Admin\Class\Review\ReviewManagement;
use Tarikul\PersonsStore\Inc\Database\Database;
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
    }

    // public function handle_update_profile_submission()
    // {
    //     global $wpdb; // Access the global $wpdb object

    //     // Define your nonce action dynamically
    //     $nonce_action = 'update_profile_with_review_nonce';

    //     // Check nonce for security
    //     if (!Helper::verify_nonce($nonce_action)) {
    //         wp_send_json_error(['message' => 'Security check failed']);
    //         exit;
    //     }

    //     //   echo "<pre>";
    //     //   error_log(print_r($_POST, true));

    //     // Sanitize and validate input
    //     $user_data = Helper::sanitize_user_data($_POST);
    //     $review_data = Helper::sanitize_review_data($_POST);
    //     $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : 0;

    //     if (!$profile_id) {
    //         wp_send_json_error(['message' => 'Profile ID is missing or invalid']);
    //         exit;
    //     }

    //     // Initialize success tracking
    //     $success = true;

    //     // Start transaction
    //     $wpdb->query('START TRANSACTION');

    //     try {
    //         // Calculate rating
    //         $average_rating = Helper::calculate_rating($review_data);
    //         if (!$average_rating) {
    //             throw new \Exception('Failed to calculate rating');
    //         }

    //         // Process review content
    //         $review_content = Helper::content_process($review_data, $average_rating);
    //         if (!$review_content) {
    //             throw new \Exception('Failed to process review content');
    //         }

    //         // Generate PDF URL
    //         $generate_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);
    //         if (!$generate_pdf_url) {
    //             throw new \Exception('Failed to generate PDF URL');
    //         }

    //         // Update downloadable product
    //         $product_id = Helper::create_or_update_downloadable_product($user_data['first_name'], $generate_pdf_url);
    //         if (!$product_id) {
    //             throw new \Exception('Failed to create or update product');
    //         }

    //         // Update profile in the database
    //         $updated_profile = $this->db->update_user($user_data, $product_id, $profile_id);
    //         if (!$updated_profile) {
    //             throw new \Exception('Failed to update user');
    //         }

    //         // Update review in the database
    //         $review_id = $this->db->update_review($profile_id, $average_rating);
    //         if (!$review_id) {
    //             throw new \Exception('Failed to update review');
    //         }

    //         //   Helper::log_error_data($updated_profile);
    //         //     Helper::log_error_data($review_id);

    //         // Update or insert review meta
    //         foreach ($review_data as $meta_key => $meta_value) {
    //             $update_meta = $this->db->update_review_meta($review_id, $meta_key, $meta_value);
    //             if (!$update_meta) {
    //                 throw new \Exception("Failed to update review meta: $meta_key");
    //             }
    //         }

    //         // Send email (if needed, or only for admins)
    //         $email = Email::getInstance();
    //         $email->setEmailDetails($user_data['email'], 'Profile Updated!', 'Hello ' . $user_data['first_name'] . ',<br>Your profile has been updated. You can check it.');
    //         $email_sent = $email->send();
    //         if (!$email_sent) {
    //             throw new \Exception('Failed to send email');
    //         }

    //         // Commit the transaction if everything is successful
    //         $wpdb->query('COMMIT');

    //     } catch (\Exception $e) {
    //         // Rollback the transaction on error
    //         $wpdb->query('ROLLBACK');
    //         $success = false; // Set success to false if any exception occurs

    //         // Log the error (if you have a logging mechanism)
    //         Helper::log_error('Error during profile update: ' . $e->getMessage());
    //     }

    //     // get profile status 
    //     $profile = $this->db->get_profile_by_id($profile_id);
    //     $url = $profile->status === 'pending' ? 'admin.php?page=persons-store-pending-profiles' : 'admin.php?page=persons-store';

    //     // Use the static method to handle the redirection with a success or fail message
    //     $message = $success ? 'Profile updated successfully!' : 'There were errors in the update process.';
    //     Helper::handle_form_submission_result($success, admin_url($url), $message);

    //     exit; // Make sure to exit after sending the response
    // }


    public function enqueue_styles()
    {
        // Get the current screen object
        $screen = get_current_screen();

        error_log('Current screen ID: ' . $screen->id);
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

        error_log('Current screen ID: ' . $screen->id);
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
                'nonce' => wp_create_nonce('approve_reject_review_nonce'),
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