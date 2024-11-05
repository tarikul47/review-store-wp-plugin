<?php
namespace Tarikul\TJMK\Inc\Email\Class;

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
        $this->id = 'wc_tjmk_email';
        $this->title = 'TJMK Email';
        $this->description = 'This email is for sending custom notifications.';

        $this->heading = 'Important Notification';
        $this->subject = 'Your Custom Notification';
        $this->template_html = 'Template/tjmk-html-email.php';
        $this->template_plain = 'Template/tjmk-plain-email.php';

        $this->message_body = '';

        // Trigger on new add profile
        //    add_action('tjmk_trigger_ajax_email', [$this, 'trigger'], 10, 2);

        // Admin profile create and email send to profile 
        add_action('tjmk_trigger_profile_created_by_admin_to_profile', [$this, 'profile_created_by_admin_to_profile_cb'], 10, 2);

        // Author create send to author and admin 
        add_action('tjmk_trigger_profile_created_pending_by_user_to_user', [$this, 'profile_created_pending_by_user_to_user_cb'], 10, 2);
        add_action('tjmk_trigger_profile_created_pending_by_user_to_admin', [$this, 'profile_created_pending_by_user_to_admin_cb'], 10, 2);


        // Admin publish and send to author and profile 
        add_action('tjmk_trigger_profile_published_by_admin_to_author', [$this, 'profile_published_by_admin_to_author_cb'], 10, 2);
        add_action('tjmk_trigger_profile_published_by_admin_to_profile', [$this, 'profile_published_by_admin_to_profile_cb'], 10, 2);


        // Review created and send to author and admin 
        add_action('tjmk_trigger_review_created_pending_by_user_to_user', [$this, 'review_created_pending_by_user_to_user_cb'], 10, 2);
        add_action('tjmk_trigger_review_created_pending_by_user_to_admin', [$this, 'review_created_pending_by_user_to_admin_cb'], 10, 2);


        // Admin review publish and send to author and profile 
        add_action('tjmk_trigger_review_published_to_author', [$this, 'review_published_to_author_cb'], 10, 2);
        add_action('tjmk_trigger_review_published_to_profile', [$this, 'review_published_to_profile_cb'], 10, 2);

        // Admin reject review and send to author 
        add_action('tjmk_trigger_review_reject_to_author', [$this, 'review_reject_to_author_cb'], 10, 2);


        // Initialize parent constructor
        parent::__construct();
        add_filter('woocommerce_email_footer_text', [$this, 'custom_woocommerce_email_footer_text'], 10);
    }

    public function trigger_email($email, $custom_data)
    {
        if (isset($email)) {
            $this->recipient = $email;
        }

        if (isset($custom_data)) {
            $this->custom_data = $custom_data;
        }

        if ($this->is_enabled() && $this->get_recipient() && $this->get_subject()) {
            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }
    }

    // 1. Profile Created by Admin to Profile User
    public function profile_created_by_admin_to_profile_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('profile_created_by_admin_to_profile_subject');
        $this->heading = $this->get_option('profile_created_by_admin_to_profile_heading');
        $this->message_body = $this->get_option('profile_created_by_admin_to_profile_content');

        $this->trigger_email($email, $custom_data);
    }

    // 2. Profile Created by Admin to Profile author 
    public function profile_created_pending_by_user_to_user_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('profile_created_pending_by_user_to_user_subject');
        $this->heading = $this->get_option('profile_created_pending_by_user_to_user_heading');
        $this->message_body = $this->get_option('profile_created_pending_by_user_to_user_content');

        $this->trigger_email($email, $custom_data);
    }

    // 3. Profile Created by Admin to Profile User
    public function profile_created_pending_by_user_to_admin_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('profile_created_pending_by_user_to_admin_subject');
        $this->heading = $this->get_option('profile_created_pending_by_user_to_admin_heading');
        $this->message_body = $this->get_option('profile_created_pending_by_user_to_admin_content');

        $this->trigger_email($email, $custom_data);
    }

    // 4. Profile Created by Admin to Profile User
    public function profile_published_by_admin_to_author_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('profile_published_by_admin_to_author_subject');
        $this->heading = $this->get_option('profile_published_by_admin_to_author_heading');
        $this->message_body = $this->get_option('profile_published_by_admin_to_author_content');

        $this->trigger_email($email, $custom_data);
    }

    // 5. Profile Created by Admin to Profile User
    public function profile_published_by_admin_to_profile_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('profile_published_by_admin_to_profile_subject');
        $this->heading = $this->get_option('profile_published_by_admin_to_profile_heading');
        $this->message_body = $this->get_option('profile_published_by_admin_to_profile_content');

        $this->trigger_email($email, $custom_data);
    }

    // 6. Profile Created by Admin to Profile User
    public function review_created_pending_by_user_to_user_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('review_created_pending_by_user_to_user_subject');
        $this->heading = $this->get_option('review_created_pending_by_user_to_user_heading');
        $this->message_body = $this->get_option('review_created_pending_by_user_to_user_content');

        $this->trigger_email($email, $custom_data);
    }

    // 7. Profile Created by Admin to Profile User
    public function review_created_pending_by_user_to_admin_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('review_created_pending_by_user_to_admin_subject');
        $this->heading = $this->get_option('review_created_pending_by_user_to_admin_heading');
        $this->message_body = $this->get_option('review_created_pending_by_user_to_admin_content');

        $this->trigger_email($email, $custom_data);
    }


    // 8. Profile Created by Admin to Profile User
    public function review_published_to_author_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('review_published_to_author_subject');
        $this->heading = $this->get_option('review_published_to_author_heading');
        $this->message_body = $this->get_option('review_published_to_author_content');

        $this->trigger_email($email, $custom_data);
    }

    // 9. Profile Created by Admin to Profile User
    public function review_published_to_profile_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('review_published_to_profile_subject');
        $this->heading = $this->get_option('review_published_to_profile_heading');
        $this->message_body = $this->get_option('review_published_to_profile_content');

        $this->trigger_email($email, $custom_data);
    }

    // 10. Review reject to aithor 
    public function review_reject_to_author_cb($email, $custom_data)
    {
        $this->subject = $this->get_option('review_reject_to_author_subject');
        $this->heading = $this->get_option('review_reject_to_author_heading');
        $this->message_body = $this->get_option('review_reject_to_author_content');

        $this->trigger_email($email, $custom_data);
    }



    public function get_heading()
    {
        return $this->heading;
    }

    public function get_subject()
    {
        return $this->subject;
    }

    /**
     * get_content_html function.
     *
     * @since 0.1
     * @return string
     */
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
                'message_body' => $this->message_body,
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
                'custom_data' => $this->custom_data, // Pass custom_data here
            ),
            '',
            TJMK_PLUGIN_ADMIN_EMAIL_DIR
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
            TJMK_PLUGIN_ADMIN_EMAIL_DIR,
        );
    }


    public function init_form_fields()
    {
        // Initialize form fields by calling helper functions for each email section
        $this->form_fields = array_merge(
            $this->get_general_settings(),
            $this->profile_created_by_admin_to_profile(),
            $this->profile_created_pending_by_user_to_user(),
            $this->profile_created_pending_by_user_to_admin(),
            $this->profile_published_by_admin_to_author(),
            $this->profile_published_by_admin_to_profile(),
            $this->review_created_pending_by_user_to_user(),
            $this->review_created_pending_by_user_to_admin(),
            $this->review_published_to_author(),
            $this->review_published_to_profile(),
            $this->review_reject_to_author(),
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
            ),
            'email_type' => array(
                'title' => 'Email type',
                'type' => 'select',
                'description' => 'Choose which format of email to send.',
                'default' => 'html',
                'class' => 'email_type',
                'options' => array(
                    //  'plain' => __('Plain text', 'woocommerce'),
                    'html' => __('HTML', 'woocommerce'),
                    //  'multipart' => __('Multipart', 'woocommerce'),
                )
            )
        );
    }

    // 1. Profile Created with Auto-Publish by Admin (to Profile User)
    protected function profile_created_by_admin_to_profile()
    {
        return array(
            'profile_created_by_admin_to_profile' => array(
                'title' => '1. Profile Created and Published by Admin - Email to Profile User',
                'type' => 'title',
                'description' => 'Settings for the email sent when a new profile with review is published by admin. Email goes to profile user.',
            ),
            'profile_created_by_admin_to_profile_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Profile Created by admin',
            ),
            'profile_created_by_admin_to_profile_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Profile Creation Notification',
            ),
            'profile_created_by_admin_to_profile_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Hurrah! You have a profile with reviews!',
            ),
        );
    }

    // 2. Profile Created by User as Pending (Email to User)
    protected function profile_created_pending_by_user_to_user()
    {
        return array(
            'profile_created_pending_by_user_to_user' => array(
                'title' => '2. Profile Created by User - Pending - Email to User',
                'type' => 'title',
                'description' => 'Email settings for profile created by user and set to pending. Email goes to user.',
            ),
            'profile_created_pending_by_user_to_user_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Created - Pending Approval',
            ),
            'profile_created_pending_by_user_to_user_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Profile Submission Notification',
            ),
            'profile_created_pending_by_user_to_user_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your profile is pending approval. We’ll notify you once it is approved.',
            ),
        );
    }


    // 3. Profile Created by User as Pending (Email to Admin)

    protected function profile_created_pending_by_user_to_admin()
    {
        return array(
            'profile_created_pending_by_user_to_admin' => array(
                'title' => '3. Profile Created by User - Pending - Email to Admin',
                'type' => 'title',
                'description' => 'Email settings for profile created by user and set to pending. Email goes to admin.',
            ),
            'profile_created_pending_by_user_to_admin_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Profile Submission - Pending Approval',
            ),
            'profile_created_pending_by_user_to_admin_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'New Profile Pending Approval',
            ),
            'profile_created_pending_by_user_to_admin_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'A new profile is awaiting your approval.',
            ),
        );
    }


    // 4. Profile Published by Admin (Email to User and Author)
    protected function profile_published_by_admin_to_author()
    {
        return array(
            'profile_published_by_admin_to_author' => array(
                'title' => '4. Profile Published by Admin - Email to Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when an admin publishes a user-created profile. Email goes to both the profile user and the author.',
            ),
            'profile_published_by_admin_to_author_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Approved and Published',
            ),
            'profile_published_by_admin_to_author_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Profile is Live!',
            ),
            'profile_published_by_admin_to_author_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Congratulations! Your profile is now live on our site.',
            ),
        );
    }

    // 5
    protected function profile_published_by_admin_to_profile()
    {
        return array(
            'profile_published_by_admin_to_profile' => array(
                'title' => '5. Profile Published by Admin - Email to Profile',
                'type' => 'title',
                'description' => 'Settings for the email sent when an admin publishes a user-created profile. Email goes to both the profile user and the author.',
            ),
            'profile_published_by_admin_to_profile_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Profile Approved and Published',
            ),
            'profile_published_by_admin_to_profile_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Profile is Live!',
            ),
            'profile_published_by_admin_to_profile_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Congratulations! Your profile is now live on our site.',
            ),
        );
    }


    // 6. Review Added by User - Pending (Email to User and Admin)
    protected function review_created_pending_by_user_to_user()
    {
        return array(
            'review_created_pending_by_user_to_user' => array(
                'title' => '6. Review Created by User - Pending - Email to User',
                'type' => 'title',
                'description' => 'Settings for the email sent when a new review is added by a user but is pending approval. Email goes to both the user and the admin.',
            ),
            'review_created_pending_by_user_to_user_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Review Pending Approval',
            ),
            'review_created_pending_by_user_to_user_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Review Submission Notification',
            ),
            'review_created_pending_by_user_to_user_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review is pending approval. We’ll notify you once it is approved.',
            ),
        );
    }

    // 7. Review Added by User - Pending (Email to User and Admin)
    protected function review_created_pending_by_user_to_admin()
    {
        return array(
            'review_created_pending_by_user_to_admin' => array(
                'title' => '7.  Review Created by User - Pending - Email to Admin',
                'type' => 'title',
                'description' => 'Settings for the email sent when a new review is added by a user but is pending approval. Email goes to both the user and the admin.',
            ),
            'review_created_pending_by_user_to_admin_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'New Review Pending Approval',
            ),
            'review_created_pending_by_user_to_admin_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Review Submission Notification',
            ),
            'review_created_pending_by_user_to_admin_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'A new review has been submitted and is awaiting your approval.',
            ),
        );
    }


    // 8. Review Published (Email to User and Profile Author)
    protected function review_published_to_author()
    {
        return array(
            'review_published_to_author' => array(
                'title' => '8. Review Published - Email to Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when a review is published. Email goes to both the user and the profile author.',
            ),
            'review_published_to_author_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Review Published Successfully',
            ),
            'review_published_to_author_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Review is Live!',
            ),
            'review_published_to_author_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review has been approved and is now visible on the profile.',
            ),
        );
    }

    // 9. Review reject (Email to Author)
    protected function review_published_to_profile()
    {
        return array(
            'review_published_to_profile' => array(
                'title' => '9. Review Published - Email to Profile User',
                'type' => 'title',
                'description' => 'Settings for the email sent when a review is published. Email goes to both the user and the profile author.',
            ),
            'review_published_to_profile_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Review Published Successfully',
            ),
            'review_published_to_profile_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Review is Live!',
            ),
            'review_published_to_profile_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review has been approved and is now visible on the profile.',
            ),
        );
    }

    // 10. Review Published (Email to User and Profile Author)
    protected function review_reject_to_author()
    {
        return array(
            'review_reject_to_author' => array(
                'title' => '10. Review Rejectd - Email to Author',
                'type' => 'title',
                'description' => 'Settings for the email sent when a review is reject. Email goes to both author.',
            ),
            'review_reject_to_author_subject' => array(
                'title' => 'Email Subject',
                'type' => 'text',
                'default' => 'Review Rejected',
            ),
            'review_reject_to_author_heading' => array(
                'title' => 'Email Heading',
                'type' => 'text',
                'default' => 'Your Review is Rejected!',
            ),
            'review_reject_to_author_content' => array(
                'title' => 'Email Content',
                'type' => 'textarea',
                'default' => 'Your review has been rejected.',
            ),
        );
    }

    public function custom_woocommerce_email_footer_text($footer_text)
    {
        // Modify the footer text based on the email ID if needed
        return esc_html__('Thank you for your business!', 'tjmk') . ' ' . esc_html__('Powered by TJMK.', 'tjmk');
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
//}

    /**
     * 1. profile_created_by_admin_to_profile 

    2. profile_created_pending_by_user_to_user

    3. profile_created_pending_by_user_to_admin

    4. profile_published_by_admin_to_author

    5. profile_published_by_admin_to_profile

    6. review_created_pending_by_user_to_user
    7. review_created_pending_by_user_to_admin

    8. review_published_to_user
    9. review_published_to_author

    TODO://
    10. Profile reject to author 
    11. Reviw reject to author 
     */

}
