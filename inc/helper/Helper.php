<?php
namespace Tarikul\PersonsStore\Inc\Helper;

class Helper
{

    // private function __construct($wpdb)
    // {
    //     // private constructor to prevent direct instantiation
    //     $this->wpdb = $wpdb;
    // }

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
            'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
            'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
            'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
            'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
            'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
            'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
        ];

        $review_content = '';

        foreach (array_keys($static_content) as $key) {
            $score = isset($review_data[$key]) ? intval($review_data[$key]) : 0;
            $review_content .= '<p>' . esc_html($static_content[$key]) . ': ' . esc_html($score) . '</p>';
        }

        // Create the review content
        $content = '<h1>Reviews for Official</h1>';
        $content .= '<h2>Review by ' . esc_html(wp_get_current_user()->display_name) . '</h2>';
        $content .= '<p>Review Content:</p>';
        $content .= $review_content;
        $content .= '<p>Average Rating: ' . esc_html($average_rating) . '</p>';
        $content .= '<hr>';

        return $content;
    }

    /**
     * Generates a PDF from the provided review content and returns the PDF URL.
     *
     * @param string $user_name   The name of the user for whom the review is being generated.
     * @param string $content     The HTML content to be included in the PDF.
     *
     * @return string|false       The URL of the generated PDF, or false on failure.
     */
    public static function generate_pdf_url($user_name, $content)
    {
        try {
            // Initialize the PDF generator
            $mpdf = new \Mpdf\Mpdf();

            // Write the content to the PDF
            $mpdf->WriteHTML($content);

            // TODO: Maybe here we don't need to sanitize because we did first phase 

            // Define the upload directory and the PDF file path
            $upload_dir = wp_upload_dir();
            $pdf_file = $upload_dir['path'] . '/user_' . sanitize_title($user_name) . '_review.pdf';

            // Output the PDF to the specified file path
            $mpdf->Output($pdf_file, 'F');

            // Set correct file permissions
            chmod($pdf_file, 0644);

            // Check if the PDF file was created successfully
            if (!file_exists($pdf_file) || filesize($pdf_file) == 0) {
                throw new Exception('PDF file creation failed or the file is empty.');
            }

            // Return the URL of the generated PDF
            return $upload_dir['url'] . '/user_' . sanitize_title($user_name) . '_review.pdf';
        } catch (Exception $e) {
            // Log any errors encountered during PDF generation
            error_log('Error generating PDF: ' . $e->getMessage());
            return false;
        }
    }

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

        // Check if product ID is provided and fetch the existing product
        if ($product_id) {
            $product = wc_get_product($product_id);
            if (!$product) {
                error_log('Product with ID ' . $product_id . ' not found.');
                return false;
            }
        } else {
            // Create a new product if no product ID is provided
            $product = new \WC_Product();
        }

        // Set product details
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

        return $product->get_id();
    }

    public static function get_current_external_profile_id_and_roles()
    {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            return [
                'external_profile_id' => $current_user->ID,
                'roles' => $current_user->roles
            ];
        }

        return null; // or return an empty array, depending on your use case
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