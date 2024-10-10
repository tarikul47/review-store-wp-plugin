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

if (isset($_GET['profile_id']) && empty($_GET['profile_id'])) {
    die('You are cheating!');
}

//echo "<pre>";
//print_r($profile_data);

?>
<h2><?php printf(__('Reviews for External Profile ID: %d', $this->plugin_text_domain), esc_html($profile_id)); ?>
</h2>

<?php if (isset($profile_data)): ?>
    <div class="profile-information">
        <div class="info1">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">First Name</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Last Name</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->last_name) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Professional Title</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->title) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Email</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->email) : 'Tarikul' ?></span>
                </div>
            </div>

        </div>
        <div class="info2">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Phone Number</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->phone) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Zip Code</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->zip_code) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">City</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->city) : 'Tarikul' ?></span>
                </div>
            </div>
        </div>
        <div class="info3">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Salary Per Month</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->salary_per_month) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Type of Employee</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->employee_type) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Region</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->region) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">State</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->state) : 'Tarikul' ?></span>
                </div>
            </div>
        </div>
        <div class="info4">
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Country</label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->country) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Municipality
                </label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->municipality) : 'Tarikul' ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Department
                </label>
                <div class="col-sm-10">
                    <span><?php echo ($profile_data !== null) ? esc_html($profile_data->department) : 'Tarikul' ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="profile-address">
        <div class="form-group-address">
            <label class="control-label col-sm-2" for="pwd">Address</label>
            <div class="col-sm-10">
                <span><?php echo ($profile_data !== null) ? esc_html($profile_data->address) : 'Tarikul' ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($reviews) { ?>
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
                $row_class = ($counter % 2 == 0) ? 'alternate' : ''; ?>
                <tr class="<?php echo esc_attr($row_class); ?>">
                    <td><?php echo esc_html($counter) . "/" . $review['review_id'] ?></td>
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
                        <?php if ($review['status'] === "approved") { ?>
                            <a href="#">Approved</a>
                        <?php } elseif ($review['status'] === "rejected") { ?>
                            <a href="#">Rejected</a>
                        <?php } else { // status is "pending" or other values ?>
                            <a class="table-btn approve_reject" data-profile-id="<?php echo esc_html($profile_id); ?>"
                                data-review-id="<?php echo esc_attr($review['review_id']); ?>" href="#">Approve</a>
                            <a class="table-btn approve_reject" data-profile-id="<?php echo esc_html($profile_id); ?>"
                                data-review-id="<?php echo esc_attr($review['review_id']); ?>" href="#">Reject</a>
                        <?php } ?>
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
                            $reviews_with_meta = $this->db->get_review_meta_by_review_id($review['review_id']);
                            foreach ($reviews_with_meta as $key => $value) { ?>
                                <li>
                                    <strong><?php echo isset($static_content[$key]) ? esc_html($static_content[$key]) : esc_html($key); ?>:</strong>
                                    <?php echo is_object($value) ? esc_html($value->meta_value) : esc_html($meta_value); ?>
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

<style>
    .profile-information {
        display: flex;
        justify-content: space-between;
        padding: 30px 10px 0px;
        width: 1100px;
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-content: center;
        align-items: center;
        padding: 15px 0px;
    }

    .profile-address {
        padding: 20px 0px;
    }

    .profile-information label.control-label {
        font-weight: 700;
    }
</style>