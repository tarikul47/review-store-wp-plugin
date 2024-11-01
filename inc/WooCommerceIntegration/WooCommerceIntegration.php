<?php
namespace Tarikul\PersonsStore\Inc\WooCommerceIntegration;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Helper\Helper;

class WooCommerceIntegration
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance();

        // Hook into WooCommerce to add custom cart item data
        add_filter('woocommerce_add_cart_item_data', [$this, 'add_person_id_to_cart_item'], 10, 2);

        // Hook to display profile ID in the cart
        //    add_filter('woocommerce_get_item_data', [$this, 'display_person_id_in_cart'], 10, 2);

        // Hook to save profile ID in order line items
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'save_person_id_in_order'], 10, 4);


        add_action('woocommerce_order_details_after_order_table', [$this, 'add_custom_link_on_thankyou_page']);

        //add_action('woocommerce_view_order', [$this, 'add_custom_link_on_thankyou_page']);

        // download review as pdf 
        add_action('wp_ajax_download_reviews', [$this, 'download_reviews_callback']);

        // login redirect 
        //   add_action('woocommerce_login_redirect', [$this, 'custom_login_redirect'], 10, 2);

    }


    /**
     * Download review as pdf after order completed 
     */
    function download_reviews_callback()
    {
        // Get order_id and person_id from the AJAX request
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $profile_id = isset($_GET['p_id']) ? intval($_GET['p_id']) : 0;

        if (!$profile_id || !$order_id) {
            wp_send_json_error('Profile ID and Order ID are required.');
        }

        error_log(print_r($order_id));
        error_log(print_r($profile_id));

        $order = wc_get_order($order_id);

        if ($order) {
            $order_date = $order->get_date_completed();
            $valid_duration = 2; // Adjust as needed

            if ($this->is_link_valid($order_date, $valid_duration)) {
                // Fetch all approved reviews
                $approved_reviews = $this->db->get_reviews('approved', $profile_id, $order_date->date('Y-m-d H:i:s'));

                //   error_log(print_r($approved_reviews, true));
                //  die();

                if (!empty($approved_reviews)) {

                    // formate order date
                    $formatted_order_date = $order_date->date('Y-m-d');

                    $profile_data = $this->db->get_profile_by_id($profile_id);

                    // profile user name
                    $profile_user_name = Helper::get_person_name_process($profile_data);

                    $fair = $this->db->get_average_meta_rating($profile_id, 'fair');
                    error_log(print_r($fair, true));


                    // Calculate rating (optional)
                    $average_rating = 0; // Implement your logic here

                    // Process review content
                    $review_content = Helper::content_process($approved_reviews, $average_rating);

                    // Load the HTML template
                    ob_start();
                    include 'pdf-template.php';  // Adjust the path to your HTML template file
                    $pdf_content = ob_get_clean();

                    // Use mPDF to generate PDF and force download
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($pdf_content);

                    // Force download the PDF
                    //   $pdf_filename = 'profile_' . $profile_id . '_reviews.pdf';

                    $pdf_filename = strtolower($profile_data->first_name) . '-' . strtolower($profile_data->last_name) . '-recensionsrapport.pdf';

                    $mpdf->Output($pdf_filename, 'D');  // 'D' forces download in the browser
                } else {
                    wp_send_json_error('No approved reviews found for this profile.');
                }
            } else {
                wp_send_json_error('Your link has expired.');
            }
        }

        wp_die();
    }

    // function download_reviews_callback()
    // {
    //     // Get order_id and person_id from the AJAX request
    //     $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    //     $profile_id = isset($_GET['p_id']) ? intval($_GET['p_id']) : 0;

    //     if (!$profile_id || !$order_id) {
    //         wp_send_json_error('Profile ID and Order ID are required.');
    //     }

    //     global $wpdb;

    //     $order = wc_get_order($order_id);
    //     $valid_duration = 2; // Adjust as needed


    //     // Fetch all approved reviews
    //     $approved_reviews = $this->db->get_reviews('approved', $profile_id);


    //     // Calculate rating
    //     $average_rating = 0; // Implement your logic here

    //     // Process review content
    //     $review_content = Helper::content_process($approved_reviews, $average_rating);

    //     //   error_log(print_r($approved_reviews, true));
    //     //      error_log(print_r($review_content, true));

    //     //   Helper::log_error_data(message: 'approved_reviews', $review_content)

    //     // Generate the PDF content
    //     $pdf_content = "<h1>Reviews for Profile ID: {$profile_id}</h1>";
    //     $pdf_content .= "<p>Average Rating: {$average_rating}</p>";
    //     $pdf_content .= $review_content;

    //     // Use mPDF to generate PDF and force download
    //     $mpdf = new \Mpdf\Mpdf();
    //     $mpdf->WriteHTML($pdf_content);

    //     // Force download the PDF
    //     $pdf_filename = 'profile_' . $profile_id . '_reviews.pdf';
    //     $mpdf->Output($pdf_filename, 'D');  // 'D' forces download in the browser

    //     wp_die();
    // }

    /**
     * Add person ID to cart item data.
     *
     * @param array $cart_item_data Cart item data.
     * @param int $product_id Product ID.
     * @return array Modified cart item data.
     */
    public function add_person_id_to_cart_item($cart_item_data, $product_id)
    {
        if (isset($_GET['p_id'])) {
            // Sanitize input using intval and sanitize_text_field for additional safety
            $cart_item_data['profile_id'] = intval(sanitize_text_field($_GET['p_id']));
        }
        return $cart_item_data;
    }

    /**
     * Display the profile ID in the cart.
     *
     * @param array $item_data Item data displayed in the cart.
     * @param array $cart_item Cart item data.
     * @return array Modified item data.
     */
    public function display_person_id_in_cart($item_data, $cart_item)
    {
        if (isset($cart_item['profile_id'])) {
            // Sanitize the output
            $item_data[] = array(
                'name' => 'Profile ID',
                'value' => sanitize_text_field($cart_item['profile_id']),
            );
        }
        return $item_data;
    }

    /**
     * Save the profile ID to the order line item.
     *
     * @param WC_Order_Item_Product $item Order item object.
     * @param string $cart_item_key Cart item key.
     * @param array $values Cart item values.
     * @param WC_Order $order The order object.
     */
    public function save_person_id_in_order($item, $cart_item_key, $values, $order)
    {
        if (isset($values['profile_id'])) {
            // Sanitize and save the profile ID as order item meta
            $item->add_meta_data('Profile ID', sanitize_text_field($values['profile_id']), true);
        }
    }

    /**
     * Add custom download link on the thank you page.
     *
     * @param WC_Order $order The order object.
     */
    public function add_custom_link_on_thankyou_page($order)
    {
        $order_id = $order->get_id();

        if (!$order_id) {
            return;
        }

        // Only show if order is completed
        if ($order && $order->get_status() === 'completed') {
            $order_date = $order->get_date_completed()->date('Y-m-d'); // Get order completion date
            $valid_duration = 2; // 2 days for Gold members, adjust this as needed

            // Check if the link is still valid
            if ($this->is_link_valid($order_date, $valid_duration)) {
                foreach ($order->get_items() as $item_id => $item) {
                    $profile_id = $item->get_meta('Profile ID');

                    if ($profile_id) {
                        // Build the AJAX URL with add_query_arg
                        $ajax_url = add_query_arg(
                            [
                                'action' => 'download_reviews',
                                'order_id' => esc_attr($order_id),
                                'p_id' => esc_attr($profile_id),
                            ],
                            admin_url('admin-ajax.php')
                        );

                        echo '<a class="submit-button button-edit" href="' . esc_url($ajax_url) . '" class="custom-link">' . esc_html__('Download Your Reviews', 'your-text-domain') . '</a><br>';
                    }
                }
            } else {
                echo esc_html__('Your link has expired.', 'your-text-domain');
            }
        }

    }

    public function is_link_valid($order_date, $valid_duration_in_days)
    {
        $valid_until = strtotime($order_date . " + $valid_duration_in_days days");
        $current_time = current_time('timestamp');

        return $current_time <= $valid_until;
    }

    public function custom_login_redirect($redirect_to, $request)
    {
        // Ensure $request is a string before processing
        if (isset($request) && is_string($request) && !empty($request)) {
            return esc_url($request);
        }

        // Default redirect if no request is available
        return get_permalink(); // Change this to the URL of your reviews page if needed
    }

}