<?php
namespace Tarikul\PersonsStore\Inc\Email;

class Email
{
    // Singleton instance
    private static $instance = null;

    // Properties
    protected $to;
    protected $subject;
    protected $message;
    protected $headers = [];
    protected $attachments = [];
    private $wpdb;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    // Get the singleton instance
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Set email details
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
     * @return bool True if email was sent, false otherwise.
     */
    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->message, $this->headers, $this->attachments);
    }

    /**
     * Add an email to the queue for later processing.
     */
    public function enqueue()
    {
        $table_name = $this->wpdb->prefix . 'urp_email_queue';
        $this->wpdb->insert(
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
    }

    /**
     * Process the email queue in batches.
     */
    public function processQueue()
    {
        $table_name = $this->wpdb->prefix . 'urp_email_queue';
        $batch_size = 3;

        $emails = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $table_name WHERE status = 'pending' LIMIT %d", $batch_size));

        foreach ($emails as $email) {
            // Create an instance for sending each email
            $this->setEmailDetails($email->to_email, $email->subject, $email->message);
            $sent = $this->send();

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
     * Schedule email processing to run every 5 minutes.
     */
    public static function scheduleProcessing()
    {
        if (!wp_next_scheduled('urp_process_email_queue_event')) {
            wp_schedule_event(time(), 'five_minutes', 'urp_process_email_queue_event');
        }
    }

    /**
     * Define a custom interval for the cron schedule.
     *
     * @param array $schedules Existing cron schedules.
     * @return array Modified cron schedules.
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

// Hook functions for WP Cron
// /add_action('urp_process_email_queue_event', function () {
//     $wpdb = $GLOBALS['wpdb'];
//     $email = Email::getInstance($wpdb);
//     $email->processQueue();
// });

// add_action('wp', function () {
//     Email::scheduleProcessing();
// });

// add_filter('cron_schedules', function ($schedules) {
//     return Email::addCustomCronSchedule($schedules);
// });
