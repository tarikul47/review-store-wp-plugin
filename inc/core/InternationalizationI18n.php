<?php

namespace Tarikul\TJMK\Inc\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 */
class InternationalizationI18n
{

    /**
     * The text domain of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $text_domain    The text domain of the plugin.
     */
    private $text_domain;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_text_domain       The text domain of this plugin.
     */
    public function __construct($plugin_text_domain)
    {

        $this->text_domain = $plugin_text_domain;

    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {

        // custom language file path 
        $mofile = PLUGIN_NAME_DIR . 'languages/tjmk-' . get_locale() . '.mo';

        if (file_exists($mofile)) {
            load_textdomain($this->text_domain, $mofile);
        }
    }

}
