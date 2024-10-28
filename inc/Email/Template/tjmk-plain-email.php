<?php
/**
 * Email templates
 */

defined('ABSPATH') || exit;

$username = html_entity_decode(esc_html__('Email : ', 'webkul'), ENT_QUOTES, 'UTF-8');
$user_email = $data['email'];
$user_obj = get_user_by('email', $user_email);
$user_name = $user_obj->first_name ? $user_obj->first_name . ' ' . $user_obj->last_name : esc_html__('Someone', 'webkul');
$admin = html_entity_decode(esc_html__('Message : ', 'webkul'), ENT_QUOTES, 'UTF-8');
$admin_message = html_entity_decode($data['message']);
$reference = html_entity_decode(esc_html__('Subject : ', 'webkul'), ENT_QUOTES, 'UTF-8');
$reference_message = html_entity_decode($data['subject'], ENT_QUOTES, 'UTF-8');

do_action('woocommerce_email_header', $email_heading, $email);

$result = '<p>' . html_entity_decode(esc_html__('Hi', 'webkul'), ENT_QUOTES, 'UTF-8') . ', ' . $admin_email . '</p>
           <p>' . $admin_message . '</p>';

if (isset($additional_content) && !empty($additional_content)) {
    $result .= '<p> <strong>' . html_entity_decode(esc_html__('Additional Content : ', 'webkul'), ENT_QUOTES, 'UTF-8') . '</strong>' . html_entity_decode($additional_content, ENT_QUOTES, 'UTF-8') . '</p>';
}

echo wp_kses_post($result);

do_action('woocommerce_email_footer', $email);