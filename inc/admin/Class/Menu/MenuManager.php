<?php
namespace Tarikul\TJMK\Inc\Admin\Class\Menu;

class MenuManager
{
    protected $menuCallbacks;
    private $plugin_name;
    private $version;
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->menuCallbacks = new MenuCallbacks($plugin_name, $version);
        add_action('admin_menu', array($this, 'tjmk_admin_menus'));
    }


    public function tjmk_admin_menus()
    {
        add_menu_page(
            __('TJMK', 'tjmk'),
            __('TJMK', 'tjmk'),
            'manage_options',
            $this->plugin_name,
            array($this->menuCallbacks, 'tjmk_profile_list_page'),
            //   PLUGIN_NAME_ASSETS_URI . '/images/tjmk-logo.png', // Path to custom image,
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Profile', 'tjmk'),
            __('Pending Profile', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-pending-profiles',
            array($this->menuCallbacks, 'tjmk_pending_profile_list_page'),
        );

        add_submenu_page(
            $this->plugin_name,
            __('Add Profile', 'tjmk'),
            __('Add Profile', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-add-profile',
            array($this->menuCallbacks, 'tjmk_add_profile_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Approve Reviews', 'tjmk'),
            __('Approve Reviews', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-approve-reviews',
            array($this->menuCallbacks, 'tjmk_approve_reviews_page')
        );

        add_submenu_page(
            $this->plugin_name,
            __('Pending Review', 'tjmk'),
            __('Pending Review', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-pending-review',
            array($this->menuCallbacks, 'tjmk_pending_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('View Reviews', 'tjmk'),
            __('View Reviews', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-view-reviews',
            array($this->menuCallbacks, 'tjmk_disply_reviews_page')
        );
        add_submenu_page(
            $this->plugin_name,
            __('Bulk Upload', 'tjmk'),
            __('Bulk Upload', 'tjmk'),
            'manage_options',
            $this->plugin_name . '-bulk-upload',
            array($this->menuCallbacks, 'tjmk_bulk_profiles_upload')
        );
        // add_submenu_page(
        //     $this->plugin_name,
        //     __('Settings', 'tjmk'),
        //     __('Settings', 'tjmk'),
        //     'manage_options',
        //     $this->plugin_name . '-settings',
        //     array($this->menuCallbacks, 'tjmk_settings_page')
        // );

    }

}