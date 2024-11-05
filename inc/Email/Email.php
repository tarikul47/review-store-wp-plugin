<?php
namespace Tarikul\TJMK\Inc\Email;

use Tarikul\TJMK\Inc\Email\Class\WC_TJMK_Email;

/**
 * Email Class for handling email operations including sending emails instantly, 
 * enqueuing emails for later processing, and processing the email queue in batches.
 */
class Email
{
    /**
     * @var Email|null Singleton instance of the class.
     */
    private static $instance = null;

    /**
     * @var string The recipient email address.
     */
    protected $to;

    /**
     * @var string The subject of the email.
     */
    protected $subject;

    /**
     * @var string The message body of the email.
     */
    protected $message;

    /**
     * @var array Additional headers for the email.
     */
    protected $headers = [];

    /**
     * @var array File paths to attachments for the email.
     */
    protected $attachments = [];

    /**
     * @var \wpdb The WordPress database object.
     */
    private $wpdb;

    /**
     * Private constructor to prevent direct instantiation.
     * Initializes the global $wpdb object.
     */
    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        // Register hooks within the constructor
        add_action('tjmk_process_email_queue_event', [$this, 'processQueue']);
        add_action('woocommerce_email_classes', [$this, 'tjmk_register_wc_email_class']);
        add_action('admin_enqueue_scripts', [$this, 'add_rich_editor_to_wc_email_settings']);

        // add_action('init', [$this, 'initialize_email_queue_event']);
    }

    /**
     * Get the singleton instance of the Email class.
     *
     * @return Email The singleton instance.
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
            //    error_log('Email instance created'); // Log instance creation
        } else {
            // error_log('Email instance reused'); // Log instance reuse
        }
        return self::$instance;
    }

    // function initialize_email_queue_event()
    // {
    //     if (!wp_next_scheduled('tjmk_process_email_queue_event')) {
    //         wp_schedule_single_event(time() + 300, 'tjmk_process_email_queue_event');
    //         error_log('Scheduled tjmk_process_email_queue_event for the first time');
    //     }
    // }

    public function tjmk_register_wc_email_class($email_classes)
    {
        // Register the custom email class using the fully qualified name
        $email_classes['WC_TJMK_Email'] = new WC_TJMK_Email();
        return $email_classes;
    }

    function add_rich_editor_to_wc_email_settings($hook)
    {
        if ('woocommerce_page_wc-settings' === $hook) {
            wp_enqueue_editor();
            wp_enqueue_script('wc-email-rich-editor', TJMK_PLUGIN_ADMIN_EMAIL_URL . 'js/wc-email-rich-editor.js', array('jquery'), null, true);
        }
    }


    /**
     * Set the email details including the recipient, subject, message, headers, and attachments.
     *
     * @param string $to Recipient email address.
     * @param string $subject Subject of the email.
     * @param string $message Message body of the email.
     * @param array $headers Optional. Additional headers for the email.
     * @param array $attachments Optional. File paths to attachments.
     */
    public function setEmailDetails($to, $subject, $message, $headers = [], $attachments = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
        $this->attachments = $attachments;
    }

    /**
     * Send an email instantly.
     *
     * @return bool True if the email was sent successfully, false otherwise.
     */
    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->message, $this->headers, $this->attachments);
    }

    /**
     * Add an email to the queue for later processing.
     * Inserts the email data into the `ps_email_queue` table with a status of 'pending'.
     */
    public function enqueue()
    {
        $table_name = $this->wpdb->prefix . 'ps_email_queue';
        $result = $this->wpdb->insert(
            $table_name,
            array(
                'to_email' => $this->to,
                'subject' => $this->subject,
                'message' => $this->message,
                'status' => 'pending',
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );

        // Return the ID of the inserted row, or false if the insert failed
        return $result !== false ? $this->wpdb->insert_id : false;
    }

    /**
     * Process the email queue in batches.
     * Retrieves a batch of pending emails and attempts to send each one.
     * Updates the status of each email in the database based on the success or failure of the send operation.
     */
    public function processQueue()
    {
        error_log(print_r('Email class - processQueue run', true));

        $table_name = $this->wpdb->prefix . 'ps_email_queue';
        $batch_size = 3;

        // Retrieve a batch of pending emails
        $emails = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE status = 'pending' LIMIT %d", $batch_size));

        // Check if there are no pending emails
        if (empty($emails)) {
            // No pending emails to process, cleanup the scheduled event
            $this->cleanup_cron_job();
            return; // Exit the function
        }


        foreach ($emails as $email) {
            // Prepare the email data
            $profile_data = [
                'name' => 'Random Name',
                'email' => $email->to_email,
                'id' => $email->id, // Use the email ID to update its status later
            ];

            // Send email and capture status
            $sent = $this->sendEmailNotification($profile_data);

            // Update email status based on send result
            $status = $sent ? 'sent' : 'failed';

            // Prepare the data to update
            $update_data = [
                'status' => $status,
                'last_attempt_at' => current_time('mysql'), // Get the current time in MySQL format
                'attempt_count' => $email->attempt_count + 1 // Increment the attempt count
            ];

            // Update the email record
            $this->wpdb->update(
                $table_name,
                $update_data,
                array('id' => $email->id),
                array('%s', '%s', '%d'), // Data types for status, last_attempt_at, attempt_count
                array('%d') // Data type for the WHERE clause
            );

            error_log("Email to {$profile_data['email']} - Status: {$status}");
        }

        // Schedule the next batch if there are more pending emails
        $pending_emails_count = $this->wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'");

        if ($pending_emails_count > 0) {
            // Schedule the next batch
            if (!wp_next_scheduled('tjmk_process_email_queue_event')) {
                wp_schedule_event(time(), 'tjmk_five_minutes', 'tjmk_process_email_queue_event');
            }
        } else {
            // Cleanup cron job if the queue is empty
            $this->cleanup_cron_job();
        }
    }


    public function sendEmailNotification($profile_data)
    {
        // Instantiate WooCommerce mailer
        $mailer = WC()->mailer();

        // Trigger WooCommerce email action (custom action example)
        $email_sent = do_action('tjmk_trigger_profile_created_by_admin_to_profile', $profile_data['email'], $profile_data);

        // Add logging for the send attempt
        error_log("Attempting to send email to: {$profile_data['email']}");

        // Capture send result
        $email_sent = (bool) apply_filters('wp_mail', ['to' => $profile_data['email']]);

        // Log and return the send status
        if ($email_sent) {
            error_log("Email successfully sent to: {$profile_data['email']}");
            return true;
        } else {
            error_log("Failed to send email to: {$profile_data['email']}");
            return false;
        }
    }


    /**
     * Cleanup cron job if there are no pending emails.
     */
    private function cleanup_cron_job()
    {
        // Remove cron job if there are no pending emails
        $timestamp = wp_next_scheduled('tjmk_process_email_queue_event');
        if ($timestamp !== false) {
            wp_unschedule_event($timestamp, 'tjmk_process_email_queue_event');
        }
    }

}

// Hook functions for WP Cron (Uncomment and use as needed)
/*
add_action('urp_process_email_queue_event', function () {
    $wpdb = $GLOBALS['wpdb'];
    $email = Email::getInstance();
    $email->processQueue();
});

add_action('wp', function () {
    Email::scheduleProcessing();
});

add_filter('cron_schedules', function ($schedules) {
    return Email::addCustomCronSchedule($schedules);
});
*/

// Ensure class is instantiated
// add_action('plugins_loaded', function () {
//     $email_instance = Email::getInstance();
//     var_dump($email_instance); // Check if the instance is created
//     die(); // Stop execution to see the output
// });