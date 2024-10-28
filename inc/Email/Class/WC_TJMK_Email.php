<?php
namespace Tarikul\PersonsStore\Inc\Email\Class;

/**
 * Email Class for handling email operations including sending emails instantly, 
 * enqueuing emails for later processing, and processing the email queue in batches.
 */
class WC_TJMK_Email extends \WC_Email
{
    // Define blank custom_data variable
    protected $custom_data;

    public function __construct()
    {
        // Set ID, title, and description of the email
        $this->id = 'wc_tjmk_email';
        $this->title = 'TJMK Email';
        $this->description = 'This email is for sending custom notifications.';

        // Define subject, heading, and email templates
        $this->heading = 'Important Notification';
        $this->subject = 'Your Custom Notification';
        $this->template_html = 'Template/tjmk-html-email.php';
        $this->template_plain = 'Template/tjmk-plain-email.php';

        $this->footer = esc_html__('Thanks regarding Tjmk.', 'tjmk');


        // Trigger on new add profile
        add_action('tjmk_trigger_ajax_email', [$this, 'trigger'], 10, 2);

        // Call parent constructor to initialize defaults
        parent::__construct();
    }

    /**
     * Send a custom email notification.
     *
     * @param string $recipient The recipient email address.
     * @param string $custom_content The custom content of the email.
     */
    public function trigger($email)
    {
        // Retrieve the relevant data from WooCommerce settings
        $subject = $this->get_option('email_subject', 'Default Subject');  // Default to 'Default Subject' if not set
        $heading = $this->get_option('email_heading', 'Default Heading');  // Default to 'Default Heading' if not set
        $message = $this->get_option('email_content', 'Default message content');  // Default to a placeholder if not set

        // Ensure required parameters are provided
        if (!empty($email) && !empty($subject) && !empty($message)) {
            $this->recipient = $email;

            // Assign settings-based subject, heading, and message to the email data array
            $this->data = array(
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
            );

            // Set custom subject and heading using WooCommerce settings
            $this->subject = $subject;
            $this->heading = $heading;

            // Custom headers for the email
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From: ' . get_option('admin_email') . "\r\n";
            $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            $headers .= "X-Priority: 1 (Highest)\r\n";
            $headers .= "X-MSMail-Priority: High\r\n";
            $headers .= "Importance: High\r\n";
            $this->headers = $headers;
        }

        // Check if the email is enabled and has a valid recipient, then send
        if ($this->is_enabled() && $this->get_recipient()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
    }


    /**
     * get_content_html function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_html()
    {
        return wc_get_template_html(
            $this->template_html,
            array(
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this
            ),
            '',
            PLUGIN_ADMIN_EMAIL_DIR
        );
    }


    /**
     * get_content_plain function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_plain()
    {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this
            ),
            '',
            PLUGIN_ADMIN_EMAIL_DIR,
        );
    }

    public function init_form_fields()
    {
        // Initialize form fields by calling helper functions for each email section
        $this->form_fields = array_merge(
            $this->get_general_settings(),
            $this->profile_created_and_published_by_admin_to_user_or_bulk(),
            $this->profile_created_by_user_pending_to_user(),
            $this->profile_created_by_user_pending_to_admin(),
            $this->profile_published_by_admin_to_user(),
            $this->profile_published_by_admin_to_profile(),
            $this->review_added_by_user_pending_to_user_and_admin(),
            $this->review_published_to_user(),
            $this->review_published_to_author()
        );
    }

    // General settings
    protected function get_general_settings()
    {
        return array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'type' => 'checkbox',
                'label' => 'Enable this email notification',
                'default' => 'yes',
            )
        );
    }

    // 1. Profile Created with Auto-Publish by Admin (to Profile User)
    protected function profile_created_and_published_by_admin_to_user_or_bulk()
    {
        return array(
            'profile_created_by_admin_to_user' => array(
                'title' => '1. Profile Created and Published by Admin - Email to Profile User',
                'type' => 'title',
                'description' => 'Settings for the email sent when a new profile with review is published by admin. Email goes to profile user.',
            ),
            'profile_created_by_admin_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Profile Created',
            ),
            'profile_created_by_admin_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Profile Creation Notification',
            ),
            'profile_created_by_admin_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Hurrah! You have a profile with reviews!',
            ),
        );
    }

    // 2. Profile Created by User as Pending (Email to User)
    protected function profile_created_by_user_pending_to_user()
    {
        return array(
            'profile_created_by_user_to_user' => array(
                'title' => '2. Profile Created by User - Pending - Email to User',
                'type' => 'title',
                'description' => 'Email settings for profile created by user and set to pending. Email goes to user.',
            ),
            'profile_created_by_user_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Created - Pending Approval',
            ),
            'profile_created_by_user_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Profile Submission Notification',
            ),
            'profile_created_by_user_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your profile is pending approval. We’ll notify you once it is approved.',
            ),
        );
    }

    // 3. Profile Created by User as Pending (Email to Admin)
    protected function profile_created_by_user_pending_to_admin()
    {
        return array(
            'profile_created_by_user_to_admin' => array(
                'title' => '3. Profile Created by User - Pending - Email to Admin',
                'type' => 'title',
                'description' => 'Email settings for profile created by user and set to pending. Email goes to admin.',
            ),
            'profile_created_to_admin_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Profile Submission - Pending Approval',
            ),
            'profile_created_to_admin_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'New Profile Pending Approval',
            ),
            'profile_created_to_admin_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'A new profile is awaiting your approval.',
            ),
        );
    }

    // 4. Profile Published by Admin (Email to User and Author)
    protected function profile_published_by_admin_to_user()
    {
        return array(
            'profile_published_by_admin' => array(
                'title' => '4. Profile Published by Admin - Email to User',
                'type' => 'title',
                'description' => 'Settings for the email sent when an admin publishes a user-created profile. Email goes to both the profile user and the author.',
            ),
            'profile_published_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Approved and Published',
            ),
            'profile_published_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Profile is Live!',
            ),
            'profile_published_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Congratulations! Your profile is now live on our site.',
            ),
        );
    }


    protected function profile_published_by_admin_to_profile()
    {
        return array(
            'to_author_profile_published_by_admin' => array(
                'title' => '4. Profile Published by Admin - Email to Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when an admin publishes a user-created profile. Email goes to both the profile user and the author.',
            ),
            'to_author_profile_published_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Approved and Published',
            ),
            'to_author_profile_published_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Profile is Live!',
            ),
            'to_author_profile_published_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Congratulations! Your profile is now live on our site.',
            ),
        );
    }


    // 5. Review Added by User - Pending (Email to User and Admin)
    protected function review_added_by_user_pending_to_user_and_admin()
    {
        return array(
            'review_added_pending' => array(
                'title' => '5. Review Added by User - Pending - Email to User and Admin',
                'type' => 'title',
                'description' => 'Settings for the email sent when a new review is added by a user but is pending approval. Email goes to both the user and the admin.',
            ),
            'review_added_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Review Pending Approval',
            ),
            'review_added_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Review Submission Notification',
            ),
            'review_added_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'A new review has been submitted and is awaiting your approval.',
            ),
        );
    }

    // 6. Review Published (Email to User and Profile Author)
    protected function review_published_to_user()
    {
        return array(
            'review_published' => array(
                'title' => '6. Review Published - Email to User and Profile Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when a review is published. Email goes to both the user and the profile author.',
            ),
            'review_published_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Review Published Successfully',
            ),
            'review_published_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Review is Live!',
            ),
            'review_published_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review has been approved and is now visible on the profile.',
            ),
        );
    }// 6. Review Published (Email to User and Profile Author)

    protected function review_published_to_author()
    {
        return array(
            'to_profile_review_published' => array(
                'title' => '7. Review Published - Email to Profile Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when a review is published. Email goes to both the user and the profile author.',
            ),
            'to_profile_review_published_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Review Published Successfully',
            ),
            'to_profile_review_published_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Review is Live!',
            ),
            'to_profile_review_published_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review has been approved and is now visible on the profile.',
            ),
        );
    }




    /**
     * Initialize Settings Form Fields
     *
     * @since 2.0
     */
    // public function init_form_fields()
    // {

    //     $this->form_fields = array(
    //         'enabled' => array(
    //             'title' => 'Enable/Disable',
    //             'type' => 'checkbox',
    //             'label' => 'Enable this email notification',
    //             'default' => 'yes'
    //         ),
    //         // Add a regular textarea, which we'll later convert to a rich text editor
    //         'custom_rich_message' => array(
    //             'title' => 'Custom Rich Message',
    //             'type' => 'textarea', // Use textarea type to start
    //             'description' => 'Enter the main content with formatting for this email notification.',
    //             'placeholder' => 'Enter custom message here...',
    //             'default' => '',
    //         ),





    //         // Group for "New Profile with publish" email by User 
    //         'by_user_new_profile' => array(
    //             'title' => 'Profile Added with a Review by Admin (Auto-published)',
    //             'type' => 'title',
    //             'description' => 'Settings for the email sent when a new profile is created. Email goes to Profile user.',
    //         ),
    //         'by_user_new_profile_subject' => array(
    //             'title' => 'Email Subject',
    //             'type' => 'text',
    //             'description' => 'Subject for the new profile email.',
    //             'default' => 'New Profile Created',
    //         ),
    //         'by_user_new_profile_heading' => array(
    //             'title' => 'Email Heading',
    //             'type' => 'text',
    //             'description' => 'Heading for the new profile email.',
    //             'default' => 'Profile Creation Notification',
    //         ),
    //         'by_user_new_profile_content' => array(
    //             'title' => 'Email Content',
    //             'type' => 'textarea',
    //             'description' => 'Content shown when a new profile is created.',
    //             'default' => 'Hurrah! You have a profile with reviews!',
    //         ),

    //         // Group for "Profile Approved" email
    //         'profile_approved' => array(
    //             'title' => 'Profile Approved Email Settings',
    //             'type' => 'title',
    //             'description' => 'Settings for the email sent when a profile is approved.',
    //         ),
    //         'profile_approved_subject' => array(
    //             'title' => 'Profile Approved Subject',
    //             'type' => 'text',
    //             'description' => 'Subject for the profile approved email.',
    //             'default' => 'Profile Approved',
    //         ),
    //         'profile_approved_heading' => array(
    //             'title' => 'Profile Approved Heading',
    //             'type' => 'text',
    //             'description' => 'Heading for the profile approved email.',
    //             'default' => 'Your Profile is Now Live',
    //         ),
    //         'profile_approved_content' => array(
    //             'title' => 'Profile Approved Content',
    //             'type' => 'textarea',
    //             'description' => 'Content shown when a profile is approved.',
    //             'default' => 'Congratulations! Your profile has been approved and is now live on our site.',
    //         ),

    //         // Group for "Review Pending" email
    //         'review_pending' => array(
    //             'title' => 'Review Pending Email Settings',
    //             'type' => 'title',
    //             'description' => 'Settings for the email sent when a review is pending.',
    //         ),
    //         'review_pending_subject' => array(
    //             'title' => 'Review Pending Subject',
    //             'type' => 'text',
    //             'description' => 'Subject for the review pending email.',
    //             'default' => 'Review Pending Approval',
    //         ),
    //         'review_pending_heading' => array(
    //             'title' => 'Review Pending Heading',
    //             'type' => 'text',
    //             'description' => 'Heading for the review pending email.',
    //             'default' => 'New Review Awaiting Approval',
    //         ),
    //         'review_pending_content' => array(
    //             'title' => 'Review Pending Content',
    //             'type' => 'textarea',
    //             'description' => 'Content shown when a review is pending.',
    //             'default' => 'A new review has been submitted and is awaiting your approval.',
    //         ),
    //         // 'email_type' => array(
    //         //     'title' => 'Email type',
    //         //     'type' => 'select',
    //         //     'description' => 'Choose which format of email to send.',
    //         //     'default' => 'html',
    //         //     'class' => 'email_type',
    //         //     'options' => array(
    //         //         // 'plain' => __('Plain text', 'woocommerce'),
    //         //         'html' => __('HTML', 'woocommerce'),
    //         //         //  'multipart' => __('Multipart', 'woocommerce'),
    //         //     )
    //         // )
    //     );
    // }
}
