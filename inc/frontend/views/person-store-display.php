<?php
use Tarikul\PersonsStore\Inc\Database\Database;

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */


get_header();
$db = Database::getInstance();
$users = $db->get_users_with_review_data();

?>
<div class="wrap">
    <h2>User List - <?php echo count($users); ?></h2>
    <form id="bulk-action-form" method="post">
        <p class="search-box">
            <label class="screen-reader-text" for="post-search-input">Search Users:</label>
            <input type="search" id="post-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="Search Users">
        </p>
        <table class="wp-list-table widefat fixed striped table-view-list users">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-first-name">First Name</th>
                    <th scope="col" class="manage-column column-last-name">Last Name</th>
                    <th scope="col" class="manage-column column-department">Title</th>
                    <th scope="col" class="manage-column column-department">Organization</th>
                    <th scope="col" class="manage-column column-department">Adminstration</th>
                    <th scope="col" class="manage-column column-department">Municipitaly</th>
                    <th scope="col" class="manage-column column-rating">Average Rating</th>
                    <th scope="col" class="manage-column column-actions">Buy Report</th>
                    <th scope="col" class="manage-column column-actions">Details</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr id="post-id-<?php echo esc_attr($user->profile_id); ?>">
                            <td class="column-first-name" data-colname="First Name"><?php echo esc_html($user->first_name); ?>
                            </td>
                            <td class="column-last-name" data-colname="Last Name"><?php echo esc_html($user->last_name); ?></td>
                            <td class="column-email" data-colname="Email"><?php echo esc_html($user->email); ?></td>
                            <td class="column-phone" data-colname="Phone"><?php echo esc_html($user->phone); ?></td>
                            <td class="column-state" data-colname="State"><?php echo esc_html($user->title); ?></td>
                            <td class="column-department" data-colname="Department"><?php echo esc_html($user->department); ?>
                            </td>
                            <td class="column-rating" data-colname="Average Rating">
                                <?php echo esc_html(number_format($user->average_rating, 2)); ?>
                            </td>
                            <td class="column-actions" data-colname="Actions">
                                <a class="table-btn"
                                    href="<?php echo esc_url(admin_url('admin.php?page=persons-store-add-person&action=edit-person&profile_id=' . esc_attr($user->profile_id))); ?>">Add To Cart</a>
                            </td>
                            <td class="column-actions" data-colname="Actions">
                                <a class="table-btn"
                                    href="<?php echo esc_url(get_permalink() . '?profile_id=' . $user->profile_id); ?>">See</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10"><?php _e('No person found.', 'text-domain'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-first-name">First Name</th>
                    <th scope="col" class="manage-column column-last-name">Last Name</th>
                    <th scope="col" class="manage-column column-department">Title</th>
                    <th scope="col" class="manage-column column-department">Organization</th>
                    <th scope="col" class="manage-column column-department">Adminstration</th>
                    <th scope="col" class="manage-column column-department">Municipitaly</th>
                    <th scope="col" class="manage-column column-rating">Average Rating</th>
                    <th scope="col" class="manage-column column-actions">Buy Report</th>
                    <th scope="col" class="manage-column column-actions">Details</th>
                </tr>
            </tfoot>
        </table>
    </form>

</div>
<?php
get_footer();