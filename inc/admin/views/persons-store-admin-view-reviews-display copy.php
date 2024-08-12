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

 echo "<pre>";
 var_dump($reviews);
if ($reviews) { ?>
    <h2><?php printf(__('Reviews for External Profile ID: %d', $this->plugin_text_domain), esc_html($external_profile_id)); ?>
    </h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('No.', $this->plugin_text_domain); ?></th>
                <th><?php _e('Average Rating', $this->plugin_text_domain); ?></th>
                <th><?php _e('Status', $this->plugin_text_domain); ?></th>
                <th><?php _e('Created At', $this->plugin_text_domain); ?></th>
                <th><?php _e('Review Data', $this->plugin_text_domain); ?></th>
                <th><?php _e('Action', $this->plugin_text_domain); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            foreach ($reviews as $review) {
                echo 'Processing review ID: ' . $review['review_id'] . '<br>';
                $row_class = ($counter % 2 == 0) ? 'alternate' : ''; ?>
                <tr class="<?php echo esc_attr($row_class); ?>">
                    <td><?php echo esc_html($counter); ?></td>
                    <td><?php echo esc_html($review['rating']); ?></td>
                    <td><?php echo esc_html($review['status']); ?></td>
                    <td><?php echo esc_html($review['created_at']); ?></td>
                    <td>
                        <a href="#" class="view-details-link table-btn"
                            data-review-id="<?php echo esc_attr($review['review_id']); ?>">
                            <?php _e('View Details', $this->plugin_text_domain); ?>
                        </a>
                    </td>
                    <td>
                        <a class="table-btn"
                            href="<?php echo esc_url(admin_url('admin.php?page=review-store-view-reviews&action=delete&external_profile_id=' . esc_attr($review['review_id']))); ?>"
                            onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete
                        </a>
                        <a class="table-btn"
                            href="<?php echo esc_url(admin_url('admin.php?page=review-store-view-reviews&action=approve&review_id=' . esc_attr($review['review_id']))); ?>">
                            Approve
                        </a>
                        <a class="table-btn"
                            href="<?php echo esc_url(admin_url('admin.php?page=review-store-view-reviews&action=reject&review_id=' . esc_attr($review['review_id']))); ?>">
                            Reject
                        </a>
                    </td>
                </tr>
                <tr class="review-meta-row" id="meta-<?php echo esc_attr($review['review_id']); ?>" style="display: none;">
                    <td colspan="6">
                        <ul>
                            <?php
                            $static_content = [
                                'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
                                'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
                                'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
                                'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
                                'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
                                'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
                            ];

                            foreach ($review['meta'] as $meta_key => $meta_value) { ?>
                                <li>
                                    <strong><?php echo isset($static_content[$meta_key]) ? esc_html($static_content[$meta_key]) : esc_html($meta_key); ?>:</strong>
                                    <?php echo is_object($meta_value) ? esc_html($meta_value->meta_value) : esc_html($meta_value); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <?php
                $counter++; // Increment the counter for each review
            } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p><?php _e('No reviews found for this external profile.', $this->plugin_text_domain); ?></p>
<?php } ?>