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

<?php if (isset($_GET['profile_id']) && !isset($_GET['review_id'])) { ?>
    <div class="person-edit-page">
        <h3><?php echo ($person_data !== null) ? 'Update Person' : 'Add New Person' ?></h3>
        <div class="edit-options">
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                name="<?php echo ($person_data !== null) ? 'updateperson' : 'createperson' ?>" id="createperson"
                class="validate" novalidate="novalidate">

                <?php
                $nonce = ($person_data !== null) ? 'update_profile_with_review_nonce' : 'add_profile_with_review_nonce';
                wp_nonce_field($nonce);
                ?>

                <input type="hidden" name="action"
                    value="<?php echo ($person_data !== null) ? 'update_person_profile' : 'add_user_with_review' ?>">

                <input name="profile_id" type="hidden"
                    value="<?php echo ($person_data !== null) ? esc_html($person_data->profile_id) : ''; ?>">

                <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                <table class="form-table" role="presentation">
                    <tbody>
                        <tr class="form-field">
                            <th scope="row"><label for="first_name">First Name</label></th>
                            <td>
                                <input name="first_name" type="text" id="first_name"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->first_name) : 'Tarikul' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="last_name">Last Name</label></th>
                            <td>
                                <input name="last_name" type="text" id="last_name"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->last_name) : 'Islam' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="title">Professional Title</label></th>
                            <td>
                                <input name="title" type="text" id="title"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->title) : 'WordPress Developer' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="email">Email</label></th>
                            <td>
                                <input name="email" type="email" id="email"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->email) : 'tarikul@gmail.com' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="phone">Phone Number</label></th>
                            <td>
                                <input name="phone" type="tel" id="phone"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->phone) : '01752134658' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="address">Address</label></th>
                            <td>
                                <input name="address" type="text" id="address"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->address) : '01752134658' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="zip_code">Zip Code</label></th>
                            <td>
                                <input name="zip_code" type="text" id="zip_code"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->zip_code) : '1204' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="city">City</label></th>
                            <td>
                                <input name="city" type="text" id="city"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->city) : 'Tongi' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="salary">Salary Per Month</label></th>
                            <td>
                                <input name="salary_per_month" type="number" id="salary"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->salary_per_month) : '10000000' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="employee_type">Type of Employee</label></th>
                            <td>
                                <input name="employee_type" type="text" id="employee_type"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->employee_type) : 'Freelancerss' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="region">Region</label></th>
                            <td>
                                <input name="region" type="text" id="region"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->region) : 'Region' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="state">State</label></th>
                            <td>
                                <input name="state" type="text" id="state"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->state) : 'Gazipurss' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="country">Country</label></th>
                            <td>
                                <input name="country" type="text" id="country"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->country) : 'Bangladeshss' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="municipality">Municipality</label></th>
                            <td>
                                <input name="municipality" type="text" id="municipality"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->municipality) : 'Municipalityss' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>

                        <tr class="form-field">
                            <th scope="row"><label for="department">Department</label></th>
                            <td>
                                <input name="department" type="text" id="department"
                                    value="<?php echo ($person_data !== null) ? esc_html($person_data->department) : 'Departmentss' ?>"
                                    autocapitalize="none" autocorrect="off" autocomplete="off" required>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit"><input type="submit" name="createperson" id="createpersonsub"
                        class="button button-primary"
                        value="<?php echo ($person_data !== null) ? 'Update Person' : 'Add New Person' ?>"></p>
            </form>
        </div>
    </div>
<?php } else if (isset($_GET['profile_id']) && isset($_GET['review_id'])) { ?>
        <div class="person-edit-page">
            <div class="edit-options">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="reviewupdate"
                    id="createperson" class="validate" novalidate="novalidate">

                <?php wp_nonce_field('tjmk_review_update'); ?>

                    <input type="hidden" name="action" value="tjmk_review_update">

                    <input name="profile_id" type="hidden"
                        value="<?php echo ($person_data !== null) ? esc_html($_GET['profile_id']) : '' ?>">

                    <input name="review_id" type="hidden"
                        value="<?php echo ($person_data !== null) ? esc_html($_GET['review_id']) : ''; ?>">

                    <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                    <table class="form-table" role="presentation">
                        <tbody>
                            <!-- Review Section -->
                            <tr class="form-field give-review">
                                <th scope="row" colspan="2">
                                    <h4>Update reviews to
                                        <b style="color: red;">
                                        <?php echo $this->db->get_person_name_by_id($_GET['profile_id']); ?>
                                        </b>
                                    </h4>
                                </th>
                            </tr>

                            <?php

                            $review_data = $this->db->get_review_meta_by_review_id(intval($_GET['review_id']));

                            $static_content = [
                                'fair' => 'Do you experience the official as fair and impartial (from 1 to 5)',
                                'professional' => 'Do you feel that the official has sufficient competence, is professional and qualified for his service (from 1 to 5)',
                                'response' => 'Do you feel that the official has a personal and good response (from 1 to 5)',
                                'communication' => 'Do you feel that the official has good communication, good response time (from 1 to 5)',
                                'decisions' => 'Do you feel that the official makes fair decisions (from 1 to 5)',
                                'recommend' => 'Do you recommend this official employee? (from 1 to 5)',
                                'comments' => "Say something about " . $this->db->get_person_name_by_id($_GET['profile_id']),
                            ];


                            //    echo "<pre>";
                            //   print_r($review_data);
                         

                            foreach ($static_content as $field_name => $label) {
                                // Get the corresponding value from review data
                                $value = isset($review_data[$field_name]) ? esc_attr($review_data[$field_name]->meta_value) : '';
                                ?>
                                <tr class="form-field give-review">
                                    <td>
                                        <p><?php echo esc_html($label); ?></p>
                                    </td>
                                    <td>
                                    <?php if ($field_name === 'comments'): ?>
                                            <textarea name="<?php echo esc_attr($field_name); ?>"
                                                id="<?php echo esc_attr($field_name); ?>" cols="20"
                                                rows="4"><?php echo $value; ?></textarea>
                                    <?php else: ?>
                                            <input type="number" name="<?php echo esc_attr($field_name); ?>"
                                                id="<?php echo esc_attr($field_name); ?>" value="<?php echo $value; ?>" min="1" max="5">
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                            ?>
                        </tbody>
                    </table>

                    <p class="submit"><input type="submit" name="createperson" id="createpersonsub"
                            class="button button-primary" value="Review Update"></p>
                </form>
            </div>
        </div>
<?php } else { ?>
        <div class="person-edit-page">
            <h3><?php echo ($person_data !== null) ? 'Update Person' : 'Add New Person' ?></h3>
            <div class="edit-options">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                    name="<?php echo ($person_data !== null) ? 'updateperson' : 'createperson' ?>" id="createperson"
                    class="validate" novalidate="novalidate">

                    <?php
                    $nonce = ($person_data !== null) ? 'update_profile_with_review_nonce' : 'add_profile_with_review_nonce';
                    wp_nonce_field($nonce);
                    ?>

                    <input type="hidden" name="action"
                        value="<?php echo ($person_data !== null) ? 'update_person_profile' : 'add_user_with_review' ?>">

                    <input name="profile_id" type="hidden"
                        value="<?php echo ($person_data !== null) ? esc_html($person_data->profile_id) : '' ?>">

                    <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr class="form-field">
                                <th scope="row"><label for="first_name">First Name</label></th>
                                <td>
                                    <input name="first_name" type="text" id="first_name"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->first_name) : 'Tarikul' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="last_name">Last Name</label></th>
                                <td>
                                    <input name="last_name" type="text" id="last_name"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->last_name) : 'Islam' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="title">Professional Title</label></th>
                                <td>
                                    <input name="title" type="text" id="title"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->title) : 'WordPress Developer' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="email">Email</label></th>
                                <td>
                                    <input name="email" type="email" id="email"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->email) : 'tarikul@gmail.com' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="phone">Phone Number</label></th>
                                <td>
                                    <input name="phone" type="tel" id="phone"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->phone) : '01752134658' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="address">Address</label></th>
                                <td>
                                    <input name="address" type="text" id="address"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->address) : '01752134658' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="zip_code">Zip Code</label></th>
                                <td>
                                    <input name="zip_code" type="text" id="zip_code"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->zip_code) : '1204' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="city">City</label></th>
                                <td>
                                    <input name="city" type="text" id="city"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->city) : 'Tongi' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="salary">Salary Per Month</label></th>
                                <td>
                                    <input name="salary_per_month" type="number" id="salary"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->salary_per_month) : '10000000' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="employee_type">Type of Employee</label></th>
                                <td>
                                    <input name="employee_type" type="text" id="employee_type"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->employee_type) : 'Freelancerss' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="region">Region</label></th>
                                <td>
                                    <input name="region" type="text" id="region"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->region) : 'Region' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="state">State</label></th>
                                <td>
                                    <input name="state" type="text" id="state"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->state) : 'Gazipurss' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="country">Country</label></th>
                                <td>
                                    <input name="country" type="text" id="country"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->country) : 'Bangladeshss' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="municipality">Municipality</label></th>
                                <td>
                                    <input name="municipality" type="text" id="municipality"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->municipality) : 'Municipalityss' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>

                            <tr class="form-field">
                                <th scope="row"><label for="department">Department</label></th>
                                <td>
                                    <input name="department" type="text" id="department"
                                        value="<?php echo ($person_data !== null) ? esc_html($person_data->department) : 'Departmentss' ?>"
                                        autocapitalize="none" autocorrect="off" autocomplete="off" required>
                                </td>
                            </tr>
                            <?php
                            // Conditional rendering for the "Give reviews" section
                            if (empty($profile_id)): ?>
                                <!-- Review Section -->
                                <tr class="form-field give-review">
                                    <th scope="row" colspan="2">
                                        <h4>Give reviews to Sven Nilsson?</h4>
                                    </th>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you experience the official as fair and impartial (from 1 to 5)</p>
                                    </td>
                                    <td>
                                        <input type="number" name="fair" id="fair" value="2">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you feel that the official has sufficient competence, is professional and qualified
                                            for his service (from 1 to 5)</p>
                                    </td>
                                    <td>
                                        <input type="number" name="professional" id="professional" value="3">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you feel that the official has a personal and good response (from 1 to 5)</p>
                                    </td>
                                    <td>
                                        <input type="number" name="response" id="response" value="4">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you feel that the official has good communication, good response time (from 1 to 5)
                                        </p>
                                    </td>
                                    <td>
                                        <input type="number" name="communication" id="communication" value="2">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you feel that the official makes fair decisions (from 1 to 5)</p>
                                    </td>
                                    <td>
                                        <input type="number" name="decisions" id="decisions" value="5">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Do you recommend this official employee? (from 1 to 5)</p>
                                    </td>
                                    <td>
                                        <input type="number" name="recommend" id="recommend" value="5">
                                    </td>
                                </tr>

                                <tr class="form-field give-review">
                                    <td>
                                        <p>Say something about Sven Nilsson</p>
                                    </td>
                                    <td>
                                        <textarea name="comments" id="comments" cols="20" rows="4"></textarea>
                                    </td>
                                </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>

                    <p class="submit"><input type="submit" name="createperson" id="createpersonsub"
                            class="button button-primary"
                            value="<?php echo ($person_data !== null) ? 'Update Person' : 'Add New Person' ?>"></p>
                </form>
            </div>
        </div>
<?php } 