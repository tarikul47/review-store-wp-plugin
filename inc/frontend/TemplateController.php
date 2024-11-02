<?php

namespace Tarikul\TJMK\Inc\Frontend;

/**
 * The Template functionality.
 *
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
class TemplateController
{

    public $templates;
    public function __construct()
    {
        $this->templates = [
            //'profile-store-home.php' => 'TJMK Home Template',
            'tjmk-profiles.php' => 'TJMK Profiles List Template',
            'tjmk-add-profile.php' => 'TJMK Add Profile Template'
        ];

        add_filter('theme_page_templates', [$this, 'custom_template']);
        add_filter('template_include', [$this, 'load_template']);
    }

    public function custom_template($templates)
    {
        $templates = array_merge($templates, $this->templates);

        return $templates;
    }

    public function load_template($template)
    {
        global $post;

        if (!$post) {
            return $template;
        }

        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        // Check if it's a single user view based on a query parameter or other criteria
        if (isset($_GET['profile_id']) && !empty($_GET['profile_id'])) {
            $template_name = 'tjmk-single-template.php'; // Your custom template file for single user view

            // Define the path to your custom template file
            $file = PLUGIN_FRONTEND_VIEWS_DIR . $template_name;

            // If the custom template file exists, use it
            if (file_exists($file)) {
                return $file;
            }
        }

        if (!isset($this->templates[$template_name])) {
            return $template;
        }

        $file = PLUGIN_FRONTEND_VIEWS_DIR . $template_name;

        if (file_exists($file)) {
            return $file;
        }

        return $template;
    }
}