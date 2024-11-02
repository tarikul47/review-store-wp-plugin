<?php

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

?>
<div class="profile-edit-page">
    <h3>Bulk Upload</h3>
    <form id="urp-import-form" method="post" enctype="multipart/form-data">
        <input type="file" name="user_file" required>
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('urp_import_nonce'); ?>">
        <input type="hidden" name="total_chunks" id="total_chunks" value="">
        <input type="submit" name="upload_file" class="submit-button button-edit" value="Upload">
    </form>
    <div id="import-progress-container" style="display:none;">
        <h3>Import Progress</h3>
        <div id="import-progress-bar" style="width: 0%; height: 30px; background-color: green;"></div>
        <p id="import-progress-text">0% completed</p>
    </div>
</div>
