<?php
use Tarikul\TJMK\Inc\Helper\Helper;

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
    <h2> <?php esc_html_e('All Active Profiles', 'tjmk'); ?> - <?php echo count($users); ?></h2>

    <form id="bulk-action-form" method="post">
        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input"><?php esc_html_e('Search Users:', 'tjmk'); ?></label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Users">
        </p>
        <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e('Select bulk action', 'tjmk'); ?></label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1"><?php esc_html_e('Bulk actions', 'tjmk'); ?></option>
                    <!-- <option value="edit" class="hide-if-no-js">Edit</option> -->
                    <option value="delete"><?php esc_html_e('Move to Delete', 'tjmk'); ?></option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>

            <div class="tablenav-pages one-page">
                <span class="displaying-num"><?php echo count($users); ?><?php esc_html_e('items', 'tjmk'); ?> </span>
            </div>
            <br class="clear">
        </div>

        <table class="wp-list-table widefat fixed striped table-view-list users">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="select-all" type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-first-name">Name</th>
                    <!-- <th scope="col" class="manage-column column-last-name">Last Name</th> -->
                    <th scope="col" class="manage-column column-email">Email</th>
                    <th scope="col" class="manage-column column-phone">Phone</th>
                    <th scope="col" class="manage-column column-state">Title</th>
                    <th scope="col" class="manage-column column-department">Department</th>
                    <th scope="col" class="manage-column column-rating">A.Rating</th>
                    <th scope="col" class="manage-column column-total-reviews">Total Reviews</th>
                    <th scope="col" class="manage-column column-approved-reviews">Approved Reviews</th>
                    <th scope="col" class="manage-column column-pending-reviews">Pending Reviews</th>
                    <th scope="col" class="manage-column column-actions">Author</th>
                    <th scope="col" class="manage-column column-view-reviews">View Reviews</th>
                    <th scope="col" class="manage-column column-actions">Actions</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr id="post-id-<?php echo esc_attr($user->profile_id); ?>">
                            <th scope="row" class="check-column">
                                <input id="cb-select-<?php echo esc_attr($user->profile_id); ?>" type="checkbox"
                                    name="profile_ids[]" value="<?php echo esc_attr($user->profile_id); ?>">
                            </th>
                            <td class="column-first-name" data-colname="First Name">
                                <?php echo esc_html($user->first_name . ' ' . $user->last_name); ?>
                            </td>
                            <!-- <td class="column-last-name" data-colname="Last Name"><?php //echo esc_html($user->last_name); ?></td> -->


                            <td class="column-email" data-colname="Email"><?php echo esc_html($user->email); ?></td>
                            <td class="column-phone" data-colname="Phone"><?php echo esc_html($user->phone); ?></td>
                            <td class="column-state" data-colname="title"><?php echo esc_html($user->title); ?></td>
                            <td class="column-department" data-colname="Department"><?php echo esc_html($user->department); ?>
                            </td>
                            <td class="column-rating" data-colname="Average Rating">
                                <?php echo esc_html(number_format($user->average_rating, 2)); ?>
                            </td>
                            <td class="column-total-reviews" data-colname="Total Reviews">
                                <?php echo esc_html($user->total_reviews); ?>
                            </td>
                            <td class="column-approved-reviews" data-colname="Approved Reviews">
                                <?php echo esc_html($user->approved_reviews); ?>
                            </td>
                            <td class="column-pending-reviews" data-colname="Pending Reviews">
                                <?php echo esc_html($user->pending_reviews); ?>
                            </td>
                            <td class="column-pending-reviews" data-colname="Pending Reviews">
                                <?php echo Helper::get_user_info_by_id($user->author_id)['username']; ?>
                            </td>
                            <td class="column-view-reviews" data-colname="View Reviews">
                                <a class="custom-button"
                                    href="<?php echo esc_url(admin_url('admin.php?page=tjmk-view-reviews&profile_id=' . esc_attr($user->profile_id))); ?>">View
                                    Reviews</a>
                            </td>
                            <td class="column-actions" data-colname="Actions">
                                <a class="custom-button button-edit"
                                    href="<?php echo esc_url(admin_url('admin.php?page=tjmk-add-profile&action=edit-profile&profile_id=' . esc_attr($user->profile_id))); ?>">Edit</a>

                                <a class="custom-button delete-profile-btn button-delete" href="#"
                                    data-profile-id="<?php echo esc_attr($user->profile_id); ?>">Delete</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10"><?php _e('No profile found.', 'text-domain'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input id="select-all" type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-first-name">First Name</th>
                    <th scope="col" class="manage-column column-last-name">Last Name</th>
                    <th scope="col" class="manage-column column-email">Email</th>
                    <th scope="col" class="manage-column column-phone">Phone</th>
                    <th scope="col" class="manage-column column-state">State</th>
                    <th scope="col" class="manage-column column-department">Department</th>
                    <th scope="col" class="manage-column column-rating">Average Rating</th>
                    <th scope="col" class="manage-column column-total-reviews">Total Reviews</th>
                    <th scope="col" class="manage-column column-approved-reviews">Approved Reviews</th>
                    <th scope="col" class="manage-column column-pending-reviews">Pending Reviews</th>
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
                    <!-- <option value="edit" class="hide-if-no-js">Edit</option> -->
                    <option value="delete">Move to Delete</option>
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
        const checkboxes = document.querySelectorAll('input[name="external_profile_ids[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    });
</script>