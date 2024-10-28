<?php
/**
 * Email Template
 */

defined('ABSPATH') || exit;

do_action('woocommerce_email_header', $email_heading, $email);

// Content
?>
<p><?php echo esc_html__('Hello,', 'webkul'); ?></p>
<p><?php echo esc_html__('Testing Email', 'webkul'); ?></p>

<p><?php echo esc_html($email->custom_message); ?></p>
<p><?php echo wp_kses_post($email->get_option('custom_rich_message', '')); ?></p>

<?php if (isset($additional_content) && !empty($additional_content)): ?>
    <p><strong><?php echo esc_html__('Additional Content:', 'webkul'); ?></strong>
        <?php echo wp_kses_post($additional_content); ?></p>
<?php endif; ?>

<?php
do_action('woocommerce_email_footer', $email);
