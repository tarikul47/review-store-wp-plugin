<?php
namespace Tarikul\TJMK\Inc\Admin\Class\Menu;

class MenuManager
{
    protected $menuCallbacks;
    private $plugin_name;
    private $version;
    private $plugin_text_domain;
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
        $this->menuCallbacks = new MenuCallbacks($plugin_name, $version, $plugin_text_domain);
        add_action('admin_menu', array($this, 'tjmk_admin_menus'));
    }


    public function tjmk_admin_menus()
    {
        add_menu_page(
            __('TJMK', $this->plugin_text_domain),
            __('TJMK', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name,
            array($this->menuCallbacks, 'tjmk_profile_list_page'),
            //   PLUGIN_NAME_ASSETS_URI . '/images/tjmk-logo.png', // Path to custom image,
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Profile', $this->plugin_text_domain),
            __('Pending Profile', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-pending-profiles',
            array($this->menuCallbacks, 'tjmk_pending_profile_list_page'),
        );

        add_submenu_page(
            $this->plugin_name,
            __('Add Profile', $this->plugin_text_domain),
            __('Add Profile', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-add-profile',
            array($this->menuCallbacks, 'tjmk_add_profile_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Approve Reviews', $this->plugin_text_domain),
            __('Approve Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-approve-reviews',
            array($this->menuCallbacks, 'tjmk_approve_reviews_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Review', $this->plugin_text_domain),
            __('Pending Review', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-pending-review',
            array($this->menuCallbacks, 'tjmk_pending_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('View Reviews', $this->plugin_text_domain),
            __('View Reviews', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-view-reviews',
            array($this->menuCallbacks, 'tjmk_disply_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Bulk Upload', $this->plugin_text_domain),
            __('Bulk Upload', $this->plugin_text_domain),
            'manage_options',
            $this->plugin_name . '-bulk-upload',
            array($this->menuCallbacks, 'tjmk_bulk_profiles_upload')
        );
        // add_submenu_page(
        //     $this->plugin_name,
        //     __('Settings', $this->plugin_text_domain),
        //     __('Settings', $this->plugin_text_domain),
        //     'manage_options',
        //     $this->plugin_name . '-settings',
        //     array($this->menuCallbacks, 'tjmk_settings_page')
        // );

    }

}