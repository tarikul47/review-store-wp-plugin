<?php
namespace Tarikul\PersonsStore\Inc\Email;

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

        add_action('urp_process_email_queue_event', [$this, 'processQueue']);
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
        }
        return self::$instance;
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
        $table_name = $this->wpdb->prefix . 'ps_email_queue';
        $batch_size = 3;

        // Retrieve a batch of pending emails
        $emails = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE status = 'pending' LIMIT %d", $batch_size));

        foreach ($emails as $email) {
            // Create an instance for sending each email
            $this->setEmailDetails($email->to_email, $email->subject, $email->message);
            $sent = $this->send();

            // Update email status based on send result
            $status = $sent ? 'sent' : 'failed';
            $this->wpdb->update(
                $table_name,
                array('status' => $status),
                array('id' => $email->id),
                array('%s'),
                array('%d')
            );
        }

        // Schedule the next batch processing if there are still pending emails
        $pending_emails_count = $this->wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'");

        if ($pending_emails_count > 0) {
            if (!wp_next_scheduled('urp_process_email_queue_event')) {
                wp_schedule_single_event(time() + 300, 'urp_process_email_queue_event');
            }
        }
    }

    /**
     * Schedule email queue processing to run every 5 minutes.
     * If no scheduled event exists, create one.
     */
    public static function scheduleProcessing()
    {
        if (!wp_next_scheduled('urp_process_email_queue_event')) {
            wp_schedule_event(time(), 'five_minutes', 'urp_process_email_queue_event');
        }
    }

    /**
     * Define a custom interval for the cron schedule.
     * Adds a 'five_minutes' interval to the existing cron schedules.
     *
     * @param array $schedules Existing cron schedules.
     * @return array Modified cron schedules with the custom 'five_minutes' interval.
     */
    public static function addCustomCronSchedule($schedules)
    {
        $schedules['five_minutes'] = array(
            'interval' => 300, // 5 minutes in seconds
            'display' => __('Every Five Minutes')
        );
        return $schedules;
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
