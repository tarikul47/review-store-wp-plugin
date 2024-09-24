<?php
namespace Tarikul\PersonsStore\Inc\Helper;

class Helper
{

    // private function __construct($wpdb)
    // {
    //     // private constructor to prevent direct instantiation
    //     $this->wpdb = $wpdb;
    // }

    public static function verify_nonce($action)
    {
        // Check if we are in an admin or frontend context
        if (is_admin()) {
            // For admin requests, use check_admin_referer
            if (!check_admin_referer($action)) {
                error_log('Nonce verification failed for action: ' . $action);
                return false;
            }
        } else {
            // For frontend requests, use check_ajax_referer
            if (!check_ajax_referer($action)) {
                error_log('Nonce verification failed for action: ' . $action);
                return false;
            }
        }
        return true;
    }


    public static function sanitize_user_data($data)
    {
        return [
            'first_name' => sanitize_text_field($data['first_name']),
            'last_name' => sanitize_text_field($data['last_name']),
            'title' => sanitize_text_field($data['title']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'address' => sanitize_text_field($data['address']),
            'zip_code' => sanitize_text_field($data['zip_code']),
            'city' => sanitize_text_field($data['city']),
            'salary_per_month' => sanitize_text_field($data['salary_per_month']),
            'employee_type' => sanitize_text_field($data['employee_type']),
            'region' => sanitize_text_field($data['region']),
            'state' => sanitize_text_field($data['state']),
            'country' => sanitize_text_field($data['country']),
            'municipality' => sanitize_text_field($data['municipality']),
            'department' => sanitize_text_field($data['department']),
        ];
    }

    public static function sanitize_review_data($data)
    {
        if (isset($data['action']) && $data['action'] !== 'approve_review') {
            $sanitized_data = [
                'fair' => intval($data['fair']),
                'professional' => intval($data['professional']),
                'response' => intval($data['response']),
                'communication' => intval($data['communication']),
                'decisions' => intval($data['decisions']),
                'recommend' => intval($data['recommend']),
                'comments' => sanitize_textarea_field($data['comments'])
            ];
        }

        if (isset($data['action']) && $data['action'] === 'approve_review') {
            $sanitized_data['action'] = sanitize_text_field($data['action']);
        }

        // Check if profile_id exists, sanitize and include it
        if (!empty($data['profile_id'])) {
            $sanitized_data['profile_id'] = intval($data['profile_id']);
        }

        if (!empty($data['review_id'])) {
            $sanitized_data['review_id'] = intval($data['review_id']);
        }

        return $sanitized_data;
    }

    /**
     * Calculate the average rating based on review data.
     *
     * @param array $review_data Array of review data.
     * @return float The calculated average rating.
     */
    public static function calculate_rating($review_data)
    {
        //  print_r($review_data);
        $keys = ['fair', 'professional', 'response', 'communication', 'decisions', 'recommend'];
        $total_score = 0;

        foreach ($keys as $key) {
            if (isset($review_data[$key])) {
                $total_score += intval($review_data[$key]);
            }
        }

        return $total_score / count($keys);
    }


    public static function content_process($review_data, $average_rating)
    {
        // error_log(print_r('$review_data', true));
        // error_log(print_r($review_data, true));
        // Static content that describes each review criteria
        $static_content = [
            'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
            'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
            'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
            'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
            'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
            'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
            'comments' => 'Review Message',
        ];

        // Initialize content variable
        $content = '<h1>Reviews for Official</h1>';

        // Check if $review_data is a single review or multiple reviews
        if (isset($review_data['fair']) || isset($review_data['comments'])) {
            // Single review scenario
            $review_content = self::process_single_review($review_data, $static_content);
            //   $content .= '<h2>Review by ' . esc_html(wp_get_current_user()->display_name) . '</h2>';
            $content .= '<p>Review Content:</p>';
            $content .= $review_content;
            $content .= '<p>Average Rating: ' . esc_html($average_rating) . '</p>';
            $content .= '<hr>';
        } else {
            // Multiple reviews scenario
            foreach ($review_data as $review) {
                $meta_data = $review['meta'];
                $review_content = self::process_single_review($meta_data, $static_content);
                //  $content .= '<h2>Review ID: ' . esc_html($review['review_id']) . ' by ' . esc_html(wp_get_current_user()->display_name) . '</h2>';
                $content .= $review_content;
                $content .= '<p>Rating: ' . esc_html($review['rating']) . '</p>';
                $content .= '<hr>';
            }
        }

        return $content;
    }

    // Helper function to process single review
    private static function process_single_review($review_data, $static_content)
    {
        $review_content = '';
        foreach (array_keys($static_content) as $key) {
            // Skip comments for now
            if ($key !== 'comments') {
                $score = isset($review_data[$key]) ? intval($review_data[$key]) : 0;
                $review_content .= '<p>' . esc_html($static_content[$key]) . ': ' . esc_html($score) . '</p>';
            }
        }

        // Append the comments (if available)
        if (isset($review_data['comments'])) {
            $review_content .= '<p>' . esc_html($static_content['comments']) . ': ' . esc_html($review_data['comments']) . '</p>';
        }

        return $review_content;
    }


    // public static function content_process($review_data, $average_rating)
    // {
    //     $static_content = [
    //         'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
    //         'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
    //         'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
    //         'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
    //         'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
    //         'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
    //         'comments' => 'Review Message',
    //     ];

    //     $review_content = '';

    //     foreach (array_keys($static_content) as $key) {
    //         if ($key !== 'comments') {
    //             $score = isset($review_data[$key]) ? intval($review_data[$key]) : 0;
    //             $review_content .= '<p>' . esc_html($static_content[$key]) . ': ' . esc_html($score) . '</p>';
    //         }
    //         $review_content .= '<p>' . esc_html($static_content[$key]) . ': ' . esc_html($review_data[$key]) . '</p>';

    //     }



    //     // Create the review content
    //     $content = '<h1>Reviews for Official</h1>';
    //     $content .= '<h2>Review by ' . esc_html(wp_get_current_user()->display_name) . '</h2>';
    //     $content .= '<p>Review Content:</p>';
    //     $content .= $review_content;
    //     $content .= '<p>Average Rating: ' . esc_html($average_rating) . '</p>';
    //     $content .= '<hr>';

    //     return $content;
    // }

    /**
     * Generates a PDF from the provided review content and returns the PDF URL.
     *
     * @param string $user_name   The name of the user for whom the review is being generated.
     * @param string $content     The HTML content to be included in the PDF.
     *
     * @return string|false       The URL of the generated PDF, or false on failure.
     */

    public static function generate_pdf_url($user_name, $content, $profile_id = null)
    {
        try {
            // Initialize the PDF generator
            $mpdf = new \Mpdf\Mpdf();

            // Write the content to the PDF
            $mpdf->WriteHTML($content);

            // Define the upload directory
            $upload_dir = wp_upload_dir();

            // Create the final PDF filename
            $pdf_filename = 'user_' . sanitize_title($user_name) . (isset($profile_id) ? "_{$profile_id}" : '') . '_review.pdf';
            $pdf_file = $upload_dir['path'] . '/' . $pdf_filename;

            // Remove old PDF if it exists and we have a profile_id
            if ($profile_id) {
                $old_pdf_filename = 'user_' . sanitize_title($user_name) . '_review.pdf';
                $old_pdf_file = $upload_dir['path'] . '/' . $old_pdf_filename;

                // Delete the old file if it exists
                if (file_exists($old_pdf_file)) {
                    unlink($old_pdf_file);  // Remove the previous file
                }
            }

            // Output the new PDF to the specified file path
            $mpdf->Output($pdf_file, 'F');

            // Set correct file permissions
            chmod($pdf_file, 0644);

            // Check if the PDF file was created successfully
            if (!file_exists($pdf_file) || filesize($pdf_file) == 0) {
                throw new \Exception('PDF file creation failed or the file is empty.');
            }

            // Return the URL of the generated PDF
            return $upload_dir['url'] . '/' . $pdf_filename;
        } catch (\Exception $e) {
            // Log any errors encountered during PDF generation
            error_log('Error generating PDF: ' . $e->getMessage());
            return false;
        }
    }



    // public static function generate_pdf_url($user_name, $content)
    // {
    //     try {
    //         // Initialize the PDF generator
    //         $mpdf = new \Mpdf\Mpdf();

    //         // Write the content to the PDF
    //         $mpdf->WriteHTML($content);

    //         // TODO: Maybe here we don't need to sanitize because we did first phase 

    //         // Define the upload directory and the PDF file path
    //         $upload_dir = wp_upload_dir();
    //         $pdf_file = $upload_dir['path'] . '/user_' . sanitize_title($user_name) . '_review.pdf';

    //         // Output the PDF to the specified file path
    //         $mpdf->Output($pdf_file, 'F');

    //         // Set correct file permissions
    //         chmod($pdf_file, 0644);

    //         // Check if the PDF file was created successfully
    //         if (!file_exists($pdf_file) || filesize($pdf_file) == 0) {
    //             throw new \Exception('PDF file creation failed or the file is empty.');
    //         }

    //         // Return the URL of the generated PDF
    //         return $upload_dir['url'] . '/user_' . sanitize_title($user_name) . '_review.pdf';
    //     } catch (\Exception $e) {
    //         // Log any errors encountered during PDF generation
    //         error_log('Error generating PDF: ' . $e->getMessage());
    //         return false;
    //     }
    // }

    /**
     * Creates or updates a downloadable WooCommerce product with an attached PDF.
     *
     * @param string $user_name   The name of the user for whom the product is being generated.
     * @param string $pdf_url     The URL of the PDF file to be attached.
     * @param int|null $product_id Optional. The ID of an existing product to update. If null, a new product will be created.
     *
     * @return int|false          The ID of the created or updated product, or false on failure.
     */
    public static function create_or_update_downloadable_product($user_name, $pdf_url, $product_id = null)
    {
        if (!$pdf_url) {
            error_log('Failed to generate PDF for ' . $user_name);
            return false;
        }

        if ($product_id) {
            // If product ID is provided, update the existing product
            $product = wc_get_product($product_id);
            if (!$product) {
                error_log('Product with ID ' . $product_id . ' not found.');
                return false;
            }

            // Only update the downloadable file
            $download_id = wp_generate_uuid4();
            $downloads = [
                $download_id => [
                    'name' => 'Review PDF',
                    'file' => $pdf_url
                ]
            ];
            $product->set_downloads($downloads);
            $product->save();

        } else {
            // If no product ID is provided, create a new product
            $product = new \WC_Product();
            $product->set_name('Review for ' . $user_name);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_description('Review PDF for ' . $user_name);
            $product->set_regular_price(10);
            $product->set_downloadable(true);
            $product->set_virtual(true);

            // Attach the PDF as a downloadable file
            $download_id = wp_generate_uuid4();
            $downloads = [
                $download_id => [
                    'name' => 'Review PDF',
                    'file' => $pdf_url
                ]
            ];
            $product->set_downloads($downloads);
            $product->save();
        }

        return $product->get_id();
    }


    // public static function create_or_update_downloadable_product($user_name, $pdf_url, $product_id = null)
    // {
    //     if (!$pdf_url) {
    //         error_log('Failed to generate PDF for ' . $user_name);
    //         return false;
    //     }

    //     // Check if product ID is provided and fetch the existing product
    //     if ($product_id) {
    //         $product = wc_get_product($product_id);
    //         if (!$product) {
    //             error_log('Product with ID ' . $product_id . ' not found.');
    //             return false;
    //         }
    //     } else {
    //         // Create a new product if no product ID is provided
    //         $product = new \WC_Product();
    //     }

    //     // Set product details
    //     $product->set_name('Review for ' . $user_name);
    //     $product->set_status('publish');
    //     $product->set_catalog_visibility('visible');
    //     $product->set_description('Review PDF for ' . $user_name);
    //     $product->set_regular_price(10);
    //     $product->set_downloadable(true);
    //     $product->set_virtual(true);

    //     // Attach the PDF as a downloadable file
    //     $download_id = wp_generate_uuid4();
    //     $downloads = [
    //         $download_id => [
    //             'name' => 'Review PDF',
    //             'file' => $pdf_url
    //         ]
    //     ];
    //     $product->set_downloads($downloads);
    //     $product->save();

    //     return $product->get_id();
    // }

    /**
     * Retrieve the current user's ID, roles, and name.
     *
     * If the current user has a first and last name, it will return their full name.
     * If either the first or last name is missing, it will return the username instead.
     *
     * @return array|null An associative array containing the profile ID, roles, and name (full name or username).
     *                    Returns null if the user is not logged in.
     */
    public static function get_current_user_id_and_roles()
    {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            // Get the first and last name
            $first_name = $current_user->user_firstname;
            $last_name = $current_user->user_lastname;

            // Determine the name to use (full name or username)
            $name = trim($first_name . ' ' . $last_name);
            if (empty($name)) {
                $name = $current_user->user_login;
            }

            return [
                'id' => $current_user->ID,
                'roles' => $current_user->roles,
                'name' => $name
            ];
        }

        return null; // or return an empty array, depending on your use case
    }

    /**
     * Get user's email, first name, last name, and username by user ID.
     *
     * @param int $user_id The ID of the user.
     * @return array An associative array with 'email', 'full_name', and 'username' keys.
     */
    public static function get_user_info_by_id($user_id)
    {
        // Fetch user data using user ID
        $user = get_userdata($user_id);

        if (!$user) {
            return null; // Return null if user not found
        }

        // Get user's email
        $email = $user->user_email;

        // Get first and last name from user meta
        $first_name = get_user_meta($user_id, 'first_name', true);
        $last_name = get_user_meta($user_id, 'last_name', true);

        // If first name and last name are empty, fall back to the username
        if (!empty($first_name) || !empty($last_name)) {
            $full_name = trim($first_name . ' ' . $last_name);
        } else {
            // Fall back to username if no first/last name
            $full_name = $user->user_login;
        }

        // Prepare the result array
        return [
            'email' => $email,
            'full_name' => $full_name,
            'username' => $user->user_login,
        ];
    }

    /**
     * 
     */
    public static function get_person_name_process($peron)
    {
        // If the profile is found, concatenate first_name and last_name
        if ($peron) {
            return $peron->first_name;
        }

        // Return null if the profile was not found
        return null;
    }

    /**
     * Handles the form submission result by redirecting to a specified URL with a success or failure message.
     *
     * @param bool $result Indicates success (true) or failure (false).
     * @param string $redirect_url The URL to redirect to.
     * @param string|null $message An optional custom message to display after redirection.
     */

    public static function handle_form_submission_result($result, $redirect_url, $message = null)
    {
        // Determine the query parameter based on the result
        $status = $result ? 'success=1' : 'fail=1';

        // Store the message in a transient (temporary data)
        if ($message) {
            set_transient('form_submission_message', $message, 60); // Store for 60 seconds
        }

        // Redirect to the specified URL with the status query parameter
        wp_redirect(add_query_arg($status, '', $redirect_url));

        // Ensure that no further code is executed after the redirect
        exit;
    }

}