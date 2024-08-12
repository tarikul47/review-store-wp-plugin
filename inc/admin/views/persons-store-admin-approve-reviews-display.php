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
// echo "<pre>";
// print_r($approved_reviews);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

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
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($approved_reviews); ?>
                items</span></div>
        <br class="clear">
    </div>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" class="manage-column">Person</th>
                <th scope="col" class="manage-column">Fair & Impartial</th>
                <th scope="col" class="manage-column">Professional</th>
                <th scope="col" class="manage-column">Response</th>
                <th scope="col" class="manage-column">Communication</th>
                <th scope="col" class="manage-column">Decisions</th>
                <th scope="col" class="manage-column">Recommend</th>
                <th scope="col" class="manage-column column-actions">Review Text</th>
                <th scope="col" class="manage-column column-actions">Review by</th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php if (!empty($approved_reviews)): ?>
                <?php foreach ($approved_reviews as $review): ?>
                    <tr id="post-id-<?php echo esc_attr($review['review_id']); ?>">
                        <th scope="row" class="check-column">
                            <input id="cb-select-<?php echo esc_attr($review['review_id']); ?>" type="checkbox"
                                value="<?php echo esc_attr($review['review_id']); ?>">
                        </th>
                        <td class="column-first-name" data-colname="Name">
                            <?php echo $this->db->get_person_name_by_id($review['profile_id']); ?>
                        </td>
                        <td class="column-fair-impartial" data-colname="Fair & Impartial">
                            <?php echo esc_html($review['meta']['fair'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-professional" data-colname="Professional">
                            <?php echo esc_html($review['meta']['professional'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-response" data-colname="Response">
                            <?php echo esc_html($review['meta']['response'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-communication" data-colname="Communication">
                            <?php echo esc_html($review['meta']['communication'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-decisions" data-colname="Decisions">
                            <?php echo esc_html($review['meta']['decisions'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-recommend" data-colname="Recommend">
                            <?php echo esc_html($review['meta']['recommend'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-review-text" data-colname="Review Text">
                            <?php echo esc_html($review['meta']['comments'] ?? 'N/A'); ?>
                        </td>
                        <td class="column-review-by" data-colname="Review by"><?php echo Helper::get_current_user_id_and_roles()['name']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10"><?php _e('No reviews found.', 'text-domain'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column">Name</th>
                <th scope="col" class="manage-column">Fair & Impartial</th>
                <th scope="col" class="manage-column">Professional</th>
                <th scope="col" class="manage-column">Response</th>
                <th scope="col" class="manage-column">Communication</th>
                <th scope="col" class="manage-column">Decisions</th>
                <th scope="col" class="manage-column">Recommend</th>
                <th scope="col" class="manage-column column-actions">Review Text</th>
                <th scope="col" class="manage-column column-actions">Review by</th>
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
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($approved_reviews); ?>
                items</span></div>
        <br class="clear">
    </div>
</form>