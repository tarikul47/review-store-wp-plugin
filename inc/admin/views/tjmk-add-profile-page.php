<?php
use Tarikul\TJMK\Inc\Helper\Helper;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */

//var_dump($person_data);
?>

<?php if (isset($_GET['profile_id']) && !empty($_GET['profile_id']) && isset($_GET['action']) && $_GET['action'] === 'edit-profile') {

    $profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : false;
    $person_data = $this->db->get_profile_by_id($profile_id);

    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-update-profile-form.php';

} else if (isset($_GET['profile_id']) && !empty($_GET['profile_id']) && isset($_GET['review_id']) && !empty($_GET['review_id']) && isset($_GET['action']) && $_GET['action'] === 'edit-review') {
   
    $profile_id = isset($_GET['profile_id']) ? intval($_GET['profile_id']) : false;
   // $person_data = $this->db->get_profile_by_id($profile_id);
    $review_data = $this->db->get_review_meta_by_review_id($profile_id);

    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-update-review-form.php';

} else {
    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-add-profile-form.php';
}

