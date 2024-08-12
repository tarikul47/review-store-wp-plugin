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
<div class="person-edit-page">
    <h3>Add New Person</h3>
    <div class="edit-options">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="createperson"
            id="createperson" class="validate" novalidate="novalidate">
            <?php wp_nonce_field('add_user_with_review_nonce'); ?>
            <input type="hidden" name="action" value="add_user_with_review">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field">
                        <th scope="row"><label for="first_name">First Name</label></th>
                        <td>
                            <input name="first_name" type="text" id="first_name" value="Tarikul" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="last_name">Last Name</label></th>
                        <td>
                            <input name="last_name" type="text" id="last_name" value="Islam" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="title">Professional Title</label></th>
                        <td>
                            <input name="title" type="text" id="title" value="WordPress Developer" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="email">Email</label></th>
                        <td>
                            <input name="email" type="email" id="email" value="tarikul@gmail.com" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="phone">Phone Number</label></th>
                        <td>
                            <input name="phone" type="tel" id="phone" value="01752134658" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="address">Address</label></th>
                        <td>
                            <input name="address" type="text" id="address" value="Dhaka" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="zip_code">Zip Code</label></th>
                        <td>
                            <input name="zip_code" type="text" id="zip_code" value="1204" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="city">City</label></th>
                        <td>
                            <input name="city" type="text" id="city" value="Tongi" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="salary">Salary Per Month</label></th>
                        <td>
                            <input name="salary_per_month" type="number" id="salary" value="100000"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="employee_type">Type of Employee</label></th>
                        <td>
                            <input name="employee_type" type="text" id="employee_type" value="Freelancer"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="region">Region</label></th>
                        <td>
                            <input name="region" type="text" id="region" value="Region" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="state">State</label></th>
                        <td>
                            <input name="state" type="text" id="state" value="State" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="country">Country</label></th>
                        <td>
                            <input name="country" type="text" id="country" value="Bangladesh" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="municipality">Municipality</label></th>
                        <td>
                            <input name="municipality" type="text" id="municipality" value="Municipality"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="department">Department</label></th>
                        <td>
                            <input name="department" type="text" id="department" value="Department"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

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

                </tbody>
            </table>

            <p class="submit"><input type="submit" name="createperson" id="createpersonsub"
                    class="button button-primary" value="Add Person Now"></p>
        </form>
    </div>
</div>