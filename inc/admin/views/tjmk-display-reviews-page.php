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

<div class="profile-tab">
    <h2>All information of <b><?php echo $this->db->get_person_name_by_id($_GET['profile_id']); ?></b></h2>
</div>

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
    <div class="profile-address" style="width:30%">
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Address</label>
            <div class="col-sm-10">
                <span><?php echo ($profile_data !== null) ? esc_html($profile_data->address) : 'Tarikul' ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($reviews) { ?>

    <?php
    //  echo "<pre>";
    // print_r($reviews);
    ?>
    <h2><?php printf(__('Reviews for External Profile ID: %d', 'tjmk'), esc_html($profile_id)); ?>
    </h2>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('No.', 'tjmk'); ?></th>
                <th><?php _e('Average Rating', 'tjmk'); ?></th>
                <th><?php _e('Status', 'tjmk'); ?></th>
                <th><?php _e('Created At', 'tjmk'); ?></th>
                <th><?php _e('Review Data', 'tjmk'); ?></th>
                <th><?php _e('Action', 'tjmk'); ?></th>
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
                        <a href="#" class="view-details-link custom-button"
                            data-review-id="<?php echo esc_attr($review['review_id']); ?>">
                            <?php _e('View Details', 'tjmk'); ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($review['status'] === "approved") { ?>
                            <a href="#">Approved</a>
                        <?php } elseif ($review['status'] === "rejected") { ?>
                            <a href="#">Rejected</a>
                        <?php } else { // status is "pending" or other values ?>
                            <a class="custom-button button-edit <?php echo ($profile_data->status === 'pending') ? 'unclickable' : ''; ?>"
                                href="<?php echo esc_url(admin_url('admin.php?page=tjmk-add-profile&action=edit-review&profile_id=' . esc_attr($profile_id) . '&review_id=' . esc_attr($review['review_id']) . '&return_to=tjmk-view-reviews')); ?>"
                                data-profile-id="<?php echo esc_html($profile_id); ?>"
                                data-review-id="<?php echo esc_attr($review['review_id']); ?>">
                                Edit
                            </a>


                            <a class="custom-button button-approve <?php echo ($profile_data->status === 'pending') ? 'unclickable' : 'approve_reject'; ?>"
                                data-profile-id="<?php echo esc_html($profile_id); ?>"
                                data-review-id="<?php echo esc_attr($review['review_id']); ?>" href="#">Approve</a>

                            <a class="custom-button button-reject <?php echo ($profile_data->status === 'pending') ? 'unclickable' : 'approve_reject'; ?>"
                                data-profile-id="<?php echo esc_html($profile_id); ?>"
                                data-review-id="<?php echo esc_attr($review['review_id']); ?>" href="#">Reject</a>
                        <?php } ?>
                    </td>

                </tr>
                <tr class="review-meta-row" id="meta-<?php echo esc_attr($review['review_id']); ?>" style="display: none;">
                    <td colspan="6">
                        <ul>
                            <?php
                            /**
                             * [fair] => 2
                                [professional] => 3
                                [response] => 4
                                [communication] => 2
                                [decisions] => 5
                                [recommend] => 5
                                [experience_title] => Share Your Experience with the Title
                                [review_date] => 2024-12-05
                                [contact_context] => In what context have you had contact with the official?
                                [handling_feedback] => How do you feel the official handled the situation?
                                [pursued_case] => Yes
                                [reported_authority] => If yes, which other authority or instance have you reported it to?
                                [satisfaction_needs] => If the rating is negative, what would be needed to satisfy you?
                                [employment_status] => yes
                                [comments_official] => Share your experience or provide feedback about the official
                             */
                            $static_content = [
                                'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
                                'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
                                'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
                                'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
                                'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
                                'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
                                'experience_title' => 'Share Your Experience with the Title',
                                'review_date' => 'In which month and year does your review refer to this person?',
                                'contact_context' => 'In what context have you had contact with the official?',
                                'handling_feedback' => 'How do you feel the official handled the situation?',
                                'pursued_case' => 'Have you pursued your case further, such as reporting it to another authority?',
                                'reported_authority' => 'If yes, which other authority or instance have you reported it to?',
                                'satisfaction_needs' => 'If the rating is negative, what would be needed to satisfy you?',
                                'employment_status' => 'Are you employed within the organization?',
                                'comments_official' => 'Share your experience or provide feedback about the official',
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
    <p><?php _e('No reviews found for this external profile.', 'tjmk'); ?></p>
<?php } ?>

<style>
    .profile-information,
    .profile-addres {
        display: flex;
        justify-content: space-between;
        padding: 30px 0px 0px;
        width: 1100px;
    }

    .profile-address {
        width: 30%;
        padding-bottom: 20px;
    }

    .form-group {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-content: center;
        align-items: center;
        padding: 15px 0px;
    }

    /* .profile-address {
        padding: 20px 0px;
    } */

    .profile-information .control-label,
    .profile-address .control-label {
        font-weight: 700;
    }

    .profile-tab {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>