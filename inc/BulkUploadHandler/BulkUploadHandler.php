<?php

namespace Tarikul\PersonsStore\Inc\BulkUploadHandler;
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Helper\Helper;
use Tarikul\PersonsStore\Inc\Email\Email;


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

            if (in_array($file_type['ext'], ['csv', 'xls', 'xlsx'])) { //  we support now only csv 
                $upload_dir = wp_upload_dir();
                $file_path = $upload_dir['path'] . '/' . basename($_FILES['user_file']['name']);
                move_uploaded_file($file, $file_path);

                // we check file extension and create a chunk size 
                $this->process_file_async($file_path, $file_type['ext']); //

            } else {
                wp_send_json_error('Invalid file type. Please upload a CSV or XLS file.');
            }
        } else {
            wp_send_json_error('No file uploaded. Please upload a CSV or XLS file.');
        }

        wp_send_json_success('Successfully Uploaded!');
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
                $chunks = [];
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

        global $wpdb; // Access the global $wpdb object

        $queue = get_option('urp_import_queue', []);
        $total_chunks = count($queue) + 1;

        if (empty($queue)) {
            wp_send_json_success('Import completed.');
        } else {
            $chunk_key = array_shift($queue);
            $chunk = get_transient($chunk_key);
            delete_transient($chunk_key);

            if ($chunk) {
                // Start transaction
                $wpdb->query('START TRANSACTION');
                $success = true; // Track success of the transaction

                try {
                    foreach ($chunk as $row) {
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

                        // Calculate rating
                        $average_rating = Helper::calculate_rating($review_data);
                        if (!$average_rating) {
                            throw new \Exception('Failed to calculate rating');
                        }

                        // Process review content
                        $review_content = Helper::content_process($review_data, $average_rating);
                        if (!$review_content) {
                            throw new \Exception('Failed to process review content');
                        }

                        // Insert person into database
                        $profile_id = $this->db->insert_user($user_data);
                        if (!$profile_id) {
                            throw new \Exception('Failed to insert user');
                        }

                        // Insert review into database
                        $review_id = $this->db->insert_review($profile_id, $average_rating);
                        if (!$review_id) {
                            throw new \Exception('Failed to insert review');
                        }

                        // Insert review meta
                        foreach ($review_data as $meta_key => $meta_value) {
                            $insert_meta = $this->db->insert_review_meta($review_id, $meta_key, $meta_value);
                            if (!$insert_meta) {
                                throw new \Exception("Failed to insert review meta: $meta_key");
                            }
                        }

                        // Prepare profile data for email
                        $profile_data = [
                            'to_email' => $user_data['email'],
                            'name' => $user_data['first_name'] . ' ' . $user_data['last_name'],
                            'profile_id' => $profile_id,
                            'status' => 'pending', // Default status
                        ];

                        // Insert email data
                        $this->db->insert_email_data($profile_data);
                    } // end foreach

                    // Commit the transaction if everything is successful
                    $wpdb->query('COMMIT');

                } catch (\Exception $e) {
                    // Rollback the transaction on error
                    $wpdb->query('ROLLBACK');
                    $success = false; // Set success to false if any exception occurs

                    // Log the error
                    Helper::log_error('Error during chunk processing: ' . $e->getMessage());
                }

                update_option('urp_import_queue', $queue);

                if ($success) {
                    wp_send_json_success([
                        'remaining' => count($queue),
                        'completed' => $chunk_key,
                        'total_chunks' => $total_chunks
                    ]);
                } else {
                    wp_send_json_error('There were errors during the processing.');
                }

            } else {
                error_log('Chunk data not found for key: ' . $chunk_key);
                wp_send_json_error('Chunk data not found.');
            }
        }
    }

}
