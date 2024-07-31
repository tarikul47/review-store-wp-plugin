<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2>User List - <?php echo count($users); ?></h2>

    <form id="posts-filter" method="get">
        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Search Pages:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Pages">
        </p>
        <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1">Bulk actions</option>
                    <option value="edit" class="hide-if-no-js">Edit</option>
                    <option value="trash">Move to Trash</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>

            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($users); ?> items</span>
            </div>
            <br class="clear">
        </div>
        <table class="wp-list-table widefat fixed striped table-view-list pages">

            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="select-all" type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-first-name">First Name</th>
                    <th scope="col" class="manage-column column-last-name">Last Name</th>
                    <th scope="col" class="manage-column column-title">Title</th>
                    <th scope="col" class="manage-column column-City">City</th>
                    <th scope="col" class="manage-column column-email">Email</th>
                    <th scope="col" class="manage-column column-reviews">Reviews</th>
                    <th scope="col" class="manage-column column-view-reviews">View Reviews</th>
                    <th scope="col" class="manage-column column-actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php foreach ($users as $user): ?>
                    <tr id="post-id-<?php echo esc_attr($user->id); ?>">
                        <th scope="row" class="check-column"> <input id="cb-select-<?php echo esc_attr($user->id); ?>"
                                type="checkbox" name="user_ids[]" value="<?php echo esc_attr($user->id); ?>"> </th>
                        <td class="column-first-name" data-colname="First Name"><?php echo esc_html($user->first_name); ?>
                        </td>
                        <td class="column-last-name" data-colname="Last Name"><?php echo esc_html($user->last_name); ?></td>
                        <td class="column-title" data-colname="Title"><?php echo esc_html($user->title); ?></td>
                        <td class="column-City" data-colname="City"><?php echo esc_html($user->city); ?></td>
                        <td class="column-email" data-colname="Email"><?php echo esc_html($user->email); ?></td>
                        <td class="column-reviews" data-colname="Reviews"><?php echo esc_html($user->reviews); ?><br> <img
                                class="review-icon-backend" src="review-4.png" alt=""></td>
                        <td class="column-view-reviews" data-colname="View Reviews"><a class="table-btn"
                                href="<?php echo esc_url(admin_url('admin.php?page=user_reviews&user_id=' . esc_attr($user->id))); ?>">View
                                Reviews</a></td>
                        <td class="column-actions" data-colname="Actions">
                            <a class="table-btn"
                                href="<?php echo esc_url(admin_url('admin.php?page=edit_user&user_id=' . esc_attr($user->id))); ?>">Edit</a>
                            <a class="table-btn"
                                href="<?php echo esc_url(admin_url('admin.php?page=user-reviews-plugin&action=delete&user_id=' . esc_attr($user->id))); ?>"
                                onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="select-all" type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-first-name">First Name</th>
                    <th scope="col" class="manage-column column-last-name">Last Name</th>
                    <th scope="col" class="manage-column column-title">Title</th>
                    <th scope="col" class="manage-column column-City">City</th>
                    <th scope="col" class="manage-column column-email">Email</th>
                    <th scope="col" class="manage-column column-reviews">Reviews</th>
                    <th scope="col" class="manage-column column-view-reviews">View Reviews</th>
                    <th scope="col" class="manage-column column-actions">Actions</th>
                </tr>
            </tfoot>

        </table>
        <div class="tablenav bottom">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
                <select name="action2" id="bulk-action-selector-bottom">
                    <option value="-1">Bulk actions</option>
                    <option value="edit" class="hide-if-no-js">Edit</option>
                    <option value="trash">Move to Trash</option>
                </select>
                <input type="submit" id="doaction2" class="button action" value="Apply">
            </div>
            <div class="alignleft actions"></div>
            <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($users); ?> items</span>
            </div>
            <br class="clear">
        </div>
    </form>
</div>

<script type="text/javascript">
    document.getElementById('select-all').addEventListener('click', function (event) {
        const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    });
</script>