<?php
namespace Tarikul\TJMK\Inc\Helper;

class Helper
{

    // private function __construct($wpdb)
    // {
    //     // private constructor to prevent direct instantiation
    //     $this->wpdb = $wpdb;
    // }

    const RATING_MIN = 1;
    const RATING_MAX = 100;

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


    public static function sanitize_user_data($data, $context = 'default')
    {
        $sanitized_data = [];
        $profilefields = ['first_name', 'last_name', 'title', 'email', 'phone', 'address', 'zip_code', 'city', 'salary_per_month', 'employee_type', 'region', 'state', 'country', 'municipality', 'department'];

        // Loop through fields and sanitize based on the field type
        foreach ($profilefields as $profilefield) {
            switch ($profilefield) {
                case 'first_name':
                case 'last_name':
                case 'title':
                case 'phone':
                case 'address':
                case 'city':
                case 'salary_per_month':
                case 'employee_type':
                case 'region':
                case 'state':
                case 'country':
                case 'municipality':
                case 'department':
                case 'zip_code':
                    $sanitized_data[$profilefield] = isset($data[$profilefield]) ? sanitize_text_field($data[$profilefield]) : '';
                    break;

                case 'email':
                    $sanitized_data[$profilefield] = isset($data[$profilefield]) ? sanitize_email($data[$profilefield]) : '';
                    break;

                // Add other cases as needed based on data type
            }
        }
        return $sanitized_data;
    }

    /**
     * 
    [fair] => 2
    [professional] => 3
    [response] => 4
    [communication] => 2
    [decisions] => 5
    [recommend] => 5
    [experience_title] => Share Your Experience with the Title
    [review_date] => 
    [contact_context] => In what context have you had contact with the official?
    [comments_official] => Share your experience or provide feedback about the official 
    [handling_feedback] => How do you feel the official handled the situation? 
    [pursued_case] => Yes
    [reported_authority] => If yes, which other authority or instance have you reported it to?
    [satisfaction_needs] => If the rating is negative, what would be needed to satisfy you? 
    [employment_status] => yes
    [submit_anonymous] => Whistleblower Protection // we can skip 
     */

    public static function sanitize_review_data($data, $context = 'default')
    {
        $sanitized_data = [
            // rating fields 
            'fair' => isset($data['fair']) ? intval($data['fair']) : 0,
            'professional' => isset($data['professional']) ? intval($data['professional']) : 0,
            'response' => isset($data['response']) ? intval($data['response']) : 0,
            'communication' => isset($data['communication']) ? intval($data['communication']) : 0,
            'decisions' => isset($data['decisions']) ? intval($data['decisions']) : 0,
            'recommend' => isset($data['recommend']) ? intval($data['recommend']) : 0,

            // extra fields
            'experience_title' => isset($data['experience_title']) ? sanitize_text_field($data['experience_title']) : '',
            'review_date' => isset($data['review_date']) ? sanitize_text_field($data['review_date']) : '',
            'contact_context' => isset($data['contact_context']) ? sanitize_text_field($data['contact_context']) : '',
            'handling_feedback' => isset($data['handling_feedback']) ? sanitize_text_field($data['handling_feedback']) : '',
            'pursued_case' => isset($data['pursued_case']) ? sanitize_text_field($data['pursued_case']) : '',
            'reported_authority' => isset($data['reported_authority']) ? sanitize_text_field($data['reported_authority']) : '',
            'satisfaction_needs' => isset($data['satisfaction_needs']) ? sanitize_text_field($data['satisfaction_needs']) : '',
            'employment_status' => isset($data['employment_status']) ? sanitize_text_field($data['employment_status']) : '',

            // comment fields 
            'comments_official' => isset($data['comments_official']) ? sanitize_textarea_field($data['comments_official']) : '',
        ];

        // Conditionally add profile_id, review_id, and action if they exist in $data
        if (isset($data['profile_id']) && $context === 'update') {
            $sanitized_data['profile_id'] = intval($data['profile_id']);
        }

        if (isset($data['review_id']) && $context === 'update') {
            $sanitized_data['review_id'] = intval($data['review_id']);
        }

        if (isset($data['action']) && $context === 'update') {
            $sanitized_data['action'] = sanitize_text_field($data['action']);
        }

        return $sanitized_data;
    }

    public static function validate_rating($rating)
    {
        return is_numeric($rating) && $rating >= self::RATING_MIN && $rating <= self::RATING_MAX;
    }

    // public static function sanitize_review_data($data, $context = 'default')
    // {
    //     $sanitized_data = [
    //         'fair' => isset($data['fair']) ? intval($data['fair']) : 0,
    //         'professional' => isset($data['professional']) ? intval($data['professional']) : 0,
    //         'response' => isset($data['response']) ? intval($data['response']) : 0,
    //         'communication' => isset($data['communication']) ? intval($data['communication']) : 0,
    //         'decisions' => isset($data['decisions']) ? intval($data['decisions']) : 0,
    //         'recommend' => isset($data['recommend']) ? intval($data['recommend']) : 0,
    //         'comments' => isset($data['comments']) ? sanitize_textarea_field($data['comments']) : '',
    //     ];

    //     // Conditionally add profile_id, review_id, and action if they exist in $data
    //     if (isset($data['profile_id'])) {
    //         $sanitized_data['profile_id'] = intval($data['profile_id']);
    //     }

    //     if (isset($data['review_id'])) {
    //         $sanitized_data['review_id'] = intval($data['review_id']);
    //     }

    //     if (isset($data['action'])) {
    //         $sanitized_data['action'] = sanitize_text_field($data['action']);
    //     }

    //     return $sanitized_data;
    // }



    public static function validate_user_data($data)
    {
        // Define required fields
        $required_fields = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'title' => 'Professional Title',
            'phone' => 'Phone Number',
            'address' => 'Address',
            'zip_code' => 'Zip Code	',
            'city' => 'City',
            'salary_per_month' => 'Salary Per Month	',
            'employee_type' => 'Type of Employee',
            'region' => 'Region',
            'state' => 'State',
            'country' => 'Country',
            'municipality' => 'Municipality',
            'department' => 'Department'
        ];

        // Check for empty required fields
        foreach ($required_fields as $field_key => $field_label) {
            if (empty($data[$field_key])) {
                return "Error: The {$field_label} field is required.";
            }
        }

        // Check for valid email
        if (!is_email($data['email'])) {
            return "Error: Please provide a valid email address.";
        }

        // Add other validation rules as needed (e.g., phone number format, zip code, etc.)

        // If all validations pass, return true
        return true;
    }


    /**
     * Calculate the average rating based on review data.
     *
     * @param array $review_data Array of review data.
     * @return float The calculated average rating.
     */
    public static function calculate_rating($review_data)
    {
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
        $static_content = [
            'fair' => 'Fair and impartial (1 to 5)',
            'professional' => 'Competence and professionalism (1 to 5)',
            'response' => 'Personal and good response (1 to 5)',
            'communication' => 'Communication and response time (1 to 5)',
            'decisions' => 'Fair decision making (1 to 5)',
            'recommend' => 'Would you recommend this official? (1 to 5)',
            'comments' => 'Additional Comments',
        ];

        $content = '<table class="table">';
        $content .= '<thead><tr>';
        foreach ($static_content as $header) {
            $content .= '<th>' . esc_html($header) . '</th>';
        }
        $content .= '</tr></thead><tbody>';

        // Check if single or multiple reviews
        if (isset($review_data['fair']) || isset($review_data['comments'])) {
            $content .= '<tr>';
            foreach (array_keys($static_content) as $key) {
                $score = isset($review_data[$key]) ? esc_html($review_data[$key]) : 'N/A';
                $content .= "<td>{$score}</td>";
            }
            $content .= '</tr>';
        } else {
            foreach ($review_data as $review) {
                $content .= '<tr>';
                foreach (array_keys($static_content) as $key) {
                    $score = isset($review['meta'][$key]) ? esc_html($review['meta'][$key]) : 'N/A';
                    $content .= "<td>{$score}</td>";
                }
                $content .= '</tr>';
            }
        }

        $content .= '</tbody></table>';

        // Append Average Rating
        $content .= '<p class="rating">Average Rating: ' . esc_html($average_rating) . '</p>';

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
                'name' => $name,
                'email' => $current_user->user_email, // Retrieve the user's email
            ];
        }

        return null; // or return an empty array, depending on your use case
    }


    /**
     * Get Admin Information
     *
     * @return array|null An array containing admin name and email, or null if not found.
     */
    public static function get_admin_info()
    {
        // Get the admin email
        $admin_email = get_option('admin_email');

        // Get the admin user object
        $admin_user = get_user_by('email', $admin_email);

        // If the admin user is found, prepare the information
        if ($admin_user) {
            // Get the admin's first and last name
            $first_name = $admin_user->first_name;
            $last_name = $admin_user->last_name;

            // Determine the admin name to use (full name or username)
            $admin_name = trim($first_name . ' ' . $last_name);
            if (empty($admin_name)) {
                $admin_name = $admin_user->user_login;
            }

            return [
                'name' => $admin_name,
                'email' => $admin_email,
            ];
        }

        return null; // Return null if the admin user is not found
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
    public static function get_person_name_process($profile)
    {
        // If the profile is found, concatenate first_name and last_name
        if ($profile) {
            return $profile->first_name . ' ' . $profile->last_name;
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

    public static function log_error($message)
    {
        if (\WP_DEBUG === true) {
            error_log('Profile Submission Error: ' . $message);
        }
    }
    public static function log_error_data($message, $data)
    {
        if (\WP_DEBUG === true) {
            error_log("Error Log - $message: " . print_r($data, true));
        }
    }

}