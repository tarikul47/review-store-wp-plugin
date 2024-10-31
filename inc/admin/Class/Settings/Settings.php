<?php
namespace Tarikul\PersonsStore\Inc\Admin\Class\Settings;

use Tarikul\PersonsStore\Inc\Database\Database;

class Settings
{
    protected $plugin_name;
    protected $version;
    protected $plugin_text_domain;
    protected $db;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        $this->db = Database::getInstance();

        // Register settings and hooks
        add_action('admin_init', [$this, 'tjmk_register_settings']);
        add_action('admin_notices', [$this, 'settings_notices']);
        add_action('update_option_tjmk_options', [$this, 'manage_cron_job']); // Trigger cron management on option update
    }

    public function tjmk_register_settings()
    {
        register_setting('tjmk_options_group', 'tjmk_options');

        // Add settings section
        add_settings_section('bulk_upload_section', 'Bulk Upload Settings', null, 'tjmk_plugin-settings');

        // Chunk size field
        add_settings_field(
            'chunk_size',
            'Chunk Size',
            [$this, 'chunk_size_field_callback'],
            'tjmk_plugin-settings',
            'bulk_upload_section'
        );

        // Cron Event Control
        add_settings_field(
            'cron_control',
            'Enable Cron Event',
            [$this, 'cron_control_field_callback'],
            'tjmk_plugin-settings',
            'bulk_upload_section'
        );
    }

    public function chunk_size_field_callback()
    {
        $options = get_option('tjmk_options');
        $chunk_size = isset($options['chunk_size']) ? esc_attr($options['chunk_size']) : '';
        echo '<input type="number" name="tjmk_options[chunk_size]" value="' . $chunk_size . '" />';
    }

    public function cron_control_field_callback()
    {
        $options = get_option('tjmk_options');
        $checked = isset($options['cron_control']) ? 'checked' : '';
        echo '<input type="checkbox" name="tjmk_options[cron_control]" value="1" ' . $checked . ' />';
    }

    public function manage_cron_job()
    {
        // Get the plugin options
        $options = get_option('tjmk_options');
        $cron_control = isset($options['cron_control']) ? $options['cron_control'] : false;

        // Check if the event is already scheduled
        $next_event = wp_next_scheduled('tjmk_process_email_queue_event');

        if ($cron_control && !$next_event) {
            // If the cron_control is enabled and the event is not scheduled
            wp_schedule_event(time(), 'tjmk_five_minutes', 'tjmk_process_email_queue_event');
            error_log(print_r('Scheduled cron event: tjmk_process_email_queue_event', true));
        } elseif (!$cron_control && $next_event) {
            // If the cron_control is disabled and the event is scheduled
            wp_unschedule_event($next_event, 'tjmk_process_email_queue_event');
            error_log(print_r('Unschedule cron event: tjmk_process_email_queue_event', true));
        }
    }

    public function settings_notices()
    {
        // Display any settings notices here
        settings_errors('tjmk_options'); // Show messages from settings API
    }
}
