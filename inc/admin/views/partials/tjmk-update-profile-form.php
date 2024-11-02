<div class="profile-edit-page">
    <h3>Update Profile</h3>
    <div class="edit-options">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="updateperson"
            id="updateperson" class="validate" novalidate="novalidate">

            <?php wp_nonce_field("tjmk_update_profile_with_review_nonce"); ?>
            <input type="hidden" name="action" value="tjmk_update_profile">

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

            <p class="submit">
                <input type="submit" name="createperson" id="createpersonsub" class="submit-button button-edit"
                    value="Update Person">
            </p>
        </form>
    </div>
</div>