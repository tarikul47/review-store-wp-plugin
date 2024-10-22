<?php
use Tarikul\PersonsStore\Inc\Helper\Helper;

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

<?php if (isset($_GET['profile_id']) && !isset($_GET['review_id'])) {
    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-update-profile-form.php';
} else if (isset($_GET['profile_id']) && isset($_GET['review_id'])) {
    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-update-review-form.php';
} else {
    require_once PLUGIN_ADMIN_VIEWS_DIR . 'partials/tjmk-add-profile-form.php';
}
