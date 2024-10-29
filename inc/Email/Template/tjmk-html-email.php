<?php
/**
 * Custom Email Template
 */

defined('ABSPATH') || exit;

do_action('woocommerce_email_header', $email_heading, $email);

?>

<h2><?php echo esc_html($email->get_subject()); ?></h2>
<?php

// error_log(print_r('$custom_data', true));
// error_log(print_r($email->get_subject(), true));

// Get user information
$user_email = $email->recipient;
$user_obj = get_user_by('email', $user_email);
$user_name = $user_obj ? ($user_obj->first_name . ' ' . $user_obj->last_name) : __('User', 'tjmk');

if (isset($custom_data['name'])) {
    $user_name = $custom_data['name'];
}
?>

<p><?php echo esc_html__('Hello,', 'tjmk') . ' ' . esc_html($user_name); ?></p>
<p><?php echo wp_kses_post($email->message_body); ?></p>

<?php do_action('woocommerce_email_footer', $email); ?>