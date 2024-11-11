<div class="profile-edit-page">
    <h3>Add New Person</h3>
    <div class="edit-options">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="createperson"
            id="createperson" class="validate" novalidate="novalidate">

            <?php wp_nonce_field('tjmk_add_profile_with_review_nonce'); ?>

            <input type="hidden" name="action" value="tjmk_add_profile_with_review">

            <input name="profile_id" type="hidden" value="">

            <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

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
                            <input name="address" type="text" id="address" value="137 A Road, Tongi"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
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
                            <input name="city" type="text" id="city" value="Gazipur" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="salary">Salary Per Month</label></th>
                        <td>
                            <input name="salary_per_month" type="number" id="salary" value="10000000"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="employee_type">Type of Employee</label></th>
                        <td>
                            <input name="employee_type" type="text" id="employee_type" value="Freelancerss"
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
                            <input name="state" type="text" id="state" value="Gazipurss" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="country">Country</label></th>
                        <td>
                            <input name="country" type="text" id="country" value="Bangladeshss" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="municipality">Municipality</label></th>
                        <td>
                            <input name="municipality" type="text" id="municipality" value="Municipalityss"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="department">Department</label></th>
                        <td>
                            <input name="department" type="text" id="department" value="Departmentss"
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
                        <!-- 1. fair -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you experience the official as fair and impartial (from 1 to 5)</p>
                            </td>
                            <td>
                                <input type="number" name="fair" id="fair" value="2">
                            </td>
                        </tr>
                        <!-- 2. professional -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you feel that the official has sufficient competence, is professional and qualified
                                    for his service (from 1 to 5)</p>
                            </td>
                            <td>
                                <input type="number" name="professional" id="professional" value="3">
                            </td>
                        </tr>
                        <!-- 3. response -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you feel that the official has a personal and good response (from 1 to 5)</p>
                            </td>
                            <td>
                                <input type="number" name="response" id="response" value="4">
                            </td>
                        </tr>
                        <!-- 4. communication -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you feel that the official has good communication, good response time (from 1 to 5)
                                </p>
                            </td>
                            <td>
                                <input type="number" name="communication" id="communication" value="2">
                            </td>
                        </tr>
                        <!-- 5. decisions -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you feel that the official makes fair decisions (from 1 to 5)</p>
                            </td>
                            <td>
                                <input type="number" name="decisions" id="decisions" value="5">
                            </td>
                        </tr>

                        <!-- 6. recommend -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Do you recommend this official employee? (from 1 to 5)</p>
                            </td>
                            <td>
                                <input type="number" name="recommend" id="recommend" value="5">
                            </td>
                        </tr>

                        <!-- Review Extra Fields Start -->

                        <!-- 7. experience_title -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Share Your Experience with the Title - experience_title</p>
                            </td>
                            <td>
                                <input type="text" name="experience_title" id="experience_title">
                            </td>
                        </tr>

                        <!-- 8. review_date -->
                        <tr class="form-field give-review">
                            <td>
                                <p>In which month and year does your review refer to this person? - review_date</p>
                            </td>
                            <td>
                                <input type="date" name="review_date" id="review_date">
                            </td>
                        </tr>
                        <!-- 9. contact_context -->
                        <tr class="form-field give-review">
                            <td>
                                <p>In what context have you had contact with the official? - contact_context</p>
                            </td>
                            <td>
                                <input type="text" name="contact_context" id="contact_context">
                            </td>
                        </tr>
                        <!-- 10. comments_official -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Share your experience or provide feedback about the official - comments_official</p>
                            </td>
                            <td>
                                <textarea name="comments_official" id="comments_official" cols="20" rows="4"></textarea>
                            </td>
                        </tr>
                        <!-- 11. handling_feedback -->
                        <tr class="form-field give-review">
                            <td>
                                <p>How do you feel the official handled the situation? - handling_feedback</p>
                            </td>
                            <td>
                                <input type="text" name="handling_feedback" id="handling_feedback">
                            </td>
                        </tr>
                        <!-- 12. pursued_case -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Have you pursued your case further, such as reporting it to another authority? -
                                    pursued_case</p>
                            </td>
                            <td>
                                <input type="radio" name="pursued_case" value="Yes" id="pursued_yes"> <label
                                    for="pursued_yes">Yes</label><br>
                                <input type="radio" name="pursued_case" value="No" id="pursued_no"> <label
                                    for="pursued_no">No</label><br>
                                <input type="radio" name="pursued_case" value="Seek Legal Advice" id="seek_legal_advice">
                                <label for="seek_legal_advice">I would like to seek legal advice regarding this
                                    case.</label>
                            </td>
                        </tr>
                        <!-- 13. reported_authority -->
                        <tr class="form-field give-review">
                            <td>
                                <p>If yes, which other authority or instance have you reported it to? - reported_authority
                                </p>
                            </td>
                            <td>
                                <input type="text" name="reported_authority" id="reported_authority"
                                    placeholder="Example: I've made a police report">
                            </td>
                        </tr>
                        <!-- 14. satisfaction_needs -->
                        <tr class="form-field give-review">
                            <td>
                                <p>If the rating is negative, what would be needed to satisfy you? - satisfaction_needs</p>
                            </td>
                            <td>
                                <input type="text" name="satisfaction_needs" id="satisfaction_needs">
                            </td>
                        </tr>
                        <!-- 15. employment_status -->
                        <tr class="form-field give-review">
                            <td>
                                <p>Are you employed within the organization? - employment_status</p>
                            </td>
                            <td>
                                <input type="radio" name="employment_status" value="yes" id="employment_status_yes">
                                <label for="employment_status_yes">Yes</label><br>

                                <input type="radio" name="employment_status" value="no" id="employment_status_no">
                                <label for="employment_status_no">No</label><br>
                            </td>
                        </tr>

                        <!-- <tr class="form-field give-review">
                            <td>
                                <p>Would you like to hide your name and submit the review anonymously? - 10</p>
                            </td>
                            <td>
                                <input type="radio" name="submit_anonymous" value="Whistleblower Protection"
                                    id="whistleblower_protection">
                                <label for="whistleblower_protection">Whistleblower Protection (50 SEK)</label><br>

                                <input type="radio" name="submit_anonymous" value="Source Protection"
                                    id="source_protection">
                                <label for="source_protection">Source Protection (25 SEK)</label><br>

                                <input type="radio" name="submit_anonymous" value="Display Name" id="display_names">
                                <label for="display_names">Display my name on the review</label>
                            </td>
                        </tr> -->

                        <!-- Review Extra Fields End -->
                    <?php endif; ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="submit-button button-edit" type="submit" name="createperson" id="createpersonsub"
                    value="Add New Person">
            </p>
        </form>
    </div>
</div>