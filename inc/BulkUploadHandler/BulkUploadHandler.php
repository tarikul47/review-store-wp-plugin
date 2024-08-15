<?php

namespace Tarikul\PersonsStore\Inc\BulkUploadHandler;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Helper\Helper;

class BulkUploadHandler
{

    private static $instance = null;
    private $db;

    private function __construct()
    {
        // Private constructor to prevent direct object creation
        $this->db = Database::getInstance();
    }

    /**
     * Get the singleton instance.
     *
     * @return BulkUploadHandler
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new BulkUploadHandler();
        }
        return self::$instance;
    }
    /**
     * Handles the file upload process.
     *
     * @return void
     */
    public function handle_file_upload()
    {
        check_ajax_referer('urp_import_nonce', 'security');

        if (!empty($_FILES['user_file']['tmp_name'])) {
            $file = $_FILES['user_file']['tmp_name'];
            $file_type = wp_check_filetype(basename($_FILES['user_file']['name']));

            if (in_array($file_type['ext'], ['csv', 'xls', 'xlsx'])) {
                $upload_dir = wp_upload_dir();
                $file_path = $upload_dir['path'] . '/' . basename($_FILES['user_file']['name']);
                move_uploaded_file($file, $file_path);

                $this->process_file_async($file_path, $file_type['ext']);
            } else {
                wp_send_json_error('Invalid file type. Please upload a CSV or XLS file.');
            }
        } else {
            wp_send_json_error('No file uploaded. Please upload a CSV or XLS file.');
        }

        wp_send_json_success('Handle upload - raju');
    }


    /**
     * Processes the uploaded file asynchronously.
     *
     * @param string $file_path The path to the uploaded file.
     * @param string $file_type The file type (csv, xls, xlsx).
     * @return void
     */
    private function process_file_async($file_path, $file_type)
    {
        $chunks = [];
        $chunk_size = 2;

        update_option('urp_import_queue', []);

        if ($file_type === 'csv') {
            $this->process_csv($file_path, $chunk_size);
        } else {
            //  $this->process_excel($file_path, $chunk_size);
        }
    }

    /**
     * Processes a CSV file and enqueues chunks.
     *
     * @param string $file_path The path to the CSV file.
     * @param int $chunk_size The size of each chunk.
     * @return void
     */
    private function process_csv($file_path, $chunk_size)
    {
        $file = fopen($file_path, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $chunks[] = $row;

            if (count($chunks) == $chunk_size) {
                $this->enqueue_chunk($chunks);
                //   error_log(print_r(count($chunks), true));
                //   error_log(print_r($chunks, true));
                $chunks = [];
                //  error_log(print_r($chunks, true));
            }
        }

        if (count($chunks) > 0) {
            $this->enqueue_chunk($chunks);
        }

        fclose($file);
    }

    /**
     * Enqueues a chunk of data.
     *
     * @param array $chunk The chunk of data to enqueue.
     * @return void
     */
    private function enqueue_chunk($chunk)
    {
        $chunk_key = 'urp_chunk_' . uniqid();
        set_transient($chunk_key, $chunk, 3600); // Store chunk data in transient

        $queue = get_option('urp_import_queue', []);
        $queue[] = $chunk_key;

        update_option('urp_import_queue', $queue); // Update the queue
    }



    /**
     * Processes queued chunks asynchronously.
     * File upload success then dynamically call the function by processChunksAsync in js file 
     * @return void
     */
    public function process_chunks_async()
    {
        check_ajax_referer('urp_import_nonce', 'security');

        $queue = get_option('urp_import_queue', []);
        $total_chunks = count($queue) + 1;

        if (empty($queue)) {
            wp_send_json_success('Import completed.');
        } else {
            $chunk_key = array_shift($queue);
            $chunk = get_transient($chunk_key);
            delete_transient($chunk_key);

            if ($chunk) {

                foreach ($chunk as $row) {
                    error_log("data upload here in database");
                    //  error_log(print_r($row, true));

                    $data = [
                        'first_name' => $row[0],
                        'last_name' => $row[1],
                        'title' => $row[2],
                        'email' => $row[3],
                        'phone' => $row[4],
                        'address' => $row[5],
                        'zip_code' => $row[6],
                        'city' => $row[7],
                        'salary_per_month' => $row[8],
                        'employee_type' => $row[9],
                        'region' => $row[10],
                        'state' => $row[11],
                        'country' => $row[12],
                        'municipality' => $row[13],
                        'department' => $row[14],
                        'fair' => $row[15],
                        'professional' => $row[16],
                        'response' => $row[17],
                        'communication' => $row[18],
                        'decisions' => $row[19],
                        'recommend' => $row[20],
                        'comments' => $row[21]
                    ];

                    // Sanitize and validate input
                    $user_data = Helper::sanitize_user_data($data);
                    $review_data = Helper::sanitize_review_data($data);

                    // Log sanitized user and review data
                    //   error_log('User Data: ' . print_r($user_data, true));
                    //   error_log('Review Data: ' . print_r($review_data, true));

                    // Calculate rating
                    $average_rating = Helper::calculate_rating($review_data);
                    if (!$average_rating) {
                        error_log('Failed to calculate rating');
                    }

                    // Process review content
                    $review_content = Helper::content_process($review_data, $average_rating);
                    if (!$review_content) {
                        error_log('Failed to process review content');
                    }

                    // Generate PDF URL
                    $generate_pdf_url = Helper::generate_pdf_url($user_data['first_name'], $review_content);
                    if (!$generate_pdf_url) {
                        error_log('Failed to generate PDF URL');
                    }

                    // Create downloadable product
                    $product_id = Helper::create_or_update_downloadable_product($user_data['first_name'], $generate_pdf_url);
                    if (!$product_id) {
                        error_log('Failed to create or update product');
                    }

                    // Insert person into database
                    $profile_id = $this->db->insert_user($user_data, $product_id);
                    if (!$profile_id) {
                        error_log('Failed to insert user');
                    }

                    // Insert review into database
                    if ($profile_id) {
                        $review_id = $this->db->insert_review($profile_id, $average_rating);
                        if (!$review_id) {
                            error_log('Failed to insert review');
                        }
                    }

                    // Insert review meta
                    if ($review_id) {
                        foreach ($review_data as $meta_key => $meta_value) {
                            $insert_meta = $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
                            if (!$insert_meta) {
                                error_log("Failed to insert review meta: $meta_key");
                            }
                        }
                    }
                } // foreach end 

                update_option('urp_import_queue', $queue);

                wp_send_json_success([
                    'remaining' => count($queue),
                    'completed' => $chunk_key,
                    'total_chunks' => $total_chunks
                ]);

            } else {
                error_log('Chunk data not found for key: ' . $chunk_key); // Add logging
                wp_send_json_error('Chunk data not found.');
            }
        }
    }

}
