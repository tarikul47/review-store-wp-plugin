<?php
namespace Tarikul\PersonsStore\Inc\Admin;

use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Email\Email;
use Tarikul\PersonsStore\Inc\Helper\Helper;

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
        $this->db = Database::getInstance();

        // Add action for form submission
        add_action('admin_post_add_user_with_review', [$this, 'handle_add_user_form_submission']);

        // Register form submission handler
        //   add_action('admin_post_handle_add_user_form', array($this, 'handle_add_user_form_submission'));

        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        // $mpdf->Output();
    }

    public function urs_admin_menu()
    {
        add_menu_page(
            __('Persons Store', $this->plugin_text_domain),
            __('Persons Store', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name,
            array($this, 'urs_user_list_page'),
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Edit Person', $this->plugin_text_domain),
            __('', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-edit-person',
            array($this, 'urs_edit_user_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Add Person', $this->plugin_text_domain),
            __('Add Person', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-add-person',
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
        add_submenu_page(
            $this->plugin_name,
            __('View Reviews', $this->plugin_text_domain),
            __('View Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-view-reviews',
            array($this, 'urs_view_reviews_page')
        );

    }

    public function urs_user_list_page()
    {
        // if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['external_profile_id'])) {
        //     $this->handle_delete_user(intval($_GET['external_profile_id']));
        // }

        // if (isset($_POST['bulk_delete_users']) && !empty($_POST['external_profile_ids'])) {
        //     $this->handle_bulk_delete_users(array_map('intval', $_POST['external_profile_ids']));
        // }

        // Display the message from the transient, if it exists
        if ($message = get_transient('form_submission_message')) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
            delete_transient('form_submission_message');
        }

        //  var_dump($_GET);

        $users = $this->db->get_users_with_review_data();

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-persons-list-display.php';
    }

    public function urs_edit_user_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-edit-person-display.php';
    }

    public function urs_add_user_page()
    {

        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-add-person-display.php';
    }

    public function urs_approve_reviews_page()
    {
        $approved_reviews = $this->db->get_approved_reviews(); // Get approved reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-approve-reviews-display.php';
    }

    public function urs_pending_reviews_page()
    {
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-pending-reviews-display.php';
    }

    public function urs_view_reviews_page()
    {
        // Get the external_profile_id from the URL parameter
        $profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : 0;

        // Fetch reviews for the selected external profile
        $reviews = $this->db->get_reviews_by_external_profile_id($profile_id);

        // $reviews_with_meta = []; // Initialize a new array to hold reviews with meta data

        // foreach ($reviews as $review) {
        //     // Fetch meta data for each review and store it in a new array
        //     $review['meta'] = $this->db->get_review_meta_by_review_id($review['review_id']);
        //     $reviews_with_meta[] = $review; // Add the modified review to the new array
        // }

        // Include the view file to display the reviews
        include_once PLUGIN_ADMIN_VIEWS_DIR . $this->plugin_name . '-admin-view-reviews-display.php';
    }



    public function handle_add_user_form_submission()
    {
        // Check nonce for security
        check_admin_referer('add_user_with_review_nonce');

        /**
         * Array
(
    [_wpnonce] => 3249128e39
    [_wp_http_referer] => /wp-admin/admin.php?page=review-store-add-person
    [action] => add_user_with_review
    [first_name] => Raju
    [last_name] => Islam
    [title] => Professional Title
    [email] => rajufresh00@gmail.com
    [phone] => 01752134658
    [address] => 53,Shabek Sharafathgonj Lane (Bobitar Goli), Distrilary Road, Gandaria
    [zip_code] => 1204
    [city] => Dhaka
    [salary] => 5000
    [employee_type] => Type of Employee
    [region] => Dhaka
    [state] => Dhaka
    [country] => Bangladesh
    [municipality] => Municipality
    [department] => Department
    [fair] => 2
    [professional] => 5
    [response] => 2
    [communication] => 1
    [decisions] => 2
    [recommend] => 3
    [comments] => Say something about Sven Nilsson
    [createperson] => Add Person Now
)
         */

        // Sanitize and validate input
        $user_data = [
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name' => sanitize_text_field($_POST['last_name']),
            'title' => sanitize_text_field($_POST['title']),
            'email' => sanitize_email($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'address' => sanitize_text_field($_POST['address']),
            'zip_code' => sanitize_text_field($_POST['zip_code']),
            'city' => sanitize_text_field($_POST['city']),
            'salary_per_month' => sanitize_text_field($_POST['salary_per_month']),
            'employee_type' => sanitize_text_field($_POST['employee_type']),
            'region' => sanitize_text_field($_POST['region']),
            'state' => sanitize_text_field($_POST['state']),
            'country' => sanitize_text_field($_POST['country']),
            'municipality' => sanitize_text_field($_POST['municipality']),
            'department' => sanitize_text_field($_POST['department']),
        ];

        $review_data = [
            'fair' => intval($_POST['fair']),
            'professional' => intval($_POST['professional']),
            'response' => intval($_POST['response']),
            'communication' => intval($_POST['communication']),
            'decisions' => intval($_POST['decisions']),
            'recommend' => intval($_POST['recommend']),
            'comments' => sanitize_textarea_field($_POST['comments'])
        ];

        // TODO: Snaitize data here 

        // TODO: Rating calculation

        $average_rating = Helper::calculate_rating($review_data); // Use the calculate_rating function

        // TODO: Review content process 

        $review_content = Helper::content_process($review_data, $average_rating);

        // TODO: Generate pdf url by review data 

        $genearte_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);

        // TODO: Create downloadable product in WooCommerce with the PDF URL

        $product_Id = Helper::create_or_update_downloadable_product($user_data['first_name'], $genearte_pdf_url);


        // TODO: Person add in Database 

        // Insert person and review into database
        $external_external_profile_id = $this->db->insert_user($user_data, $product_Id);

        // TODO: Review add in Database 
        if ($external_external_profile_id) {
            $review_id = $this->db->insert_review($external_external_profile_id, $average_rating);
        }

        if ($review_id) {
            // Insert each review meta
            foreach ($review_data as $meta_key => $meta_value) {
                $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
            }
        }

        // TODO: Email Sending 
        // Email sending for person 
        // Define email details
        $subject = 'Hurrah! A Review is live!';
        $message = 'Hello ' . $user_data['first_name'] . ',<br>One of a review is now live. You can check it.';

        // Get the singleton instance of the Email class
        $email = Email::getInstance();

        // Set email details
        $email->setEmailDetails($user_data['email'], $subject, $message);

        // Send the email
        $result = $email->send();

        // Check if the email was sent successfully
        // if ($result) {
        //     echo 'Email sent successfully!';
        //     wp_redirect(admin_url('admin.php?page=review-store&success=1'));
        // } else {
        //     echo 'Failed to send email.';
        //     wp_redirect(admin_url('admin.php?page=review-store&fail=1'));
        // }

        // Define a custom message based on the result
        // $message = $result ? 'Email sent successfully!' : 'Failed to send email.';
        $message = $result ? 'Successfully Added Person!' : 'Something worng!.';

        // Use the static method to handle the redirection with a success or fail message
        Helper::handle_form_submission_result($result, admin_url('admin.php?page=persons-store'), $message);

        //    // echo "<pre>";
        //     print_r($result);
        //     die();

        /**
         * 1. All data process with sanitize 
         * 2. pdf url = Generate pdf and store pdf url 
         * 3. Product id = Create a product / update 
         * 1. person id = Person data add 
         * 2. Email Sending instant 
         */

        // TODO: Notice showing 

        // Redirect back to the form page

        exit;
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