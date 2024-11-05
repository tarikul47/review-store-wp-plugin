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
get_header();
?>
<div id="profile-form" class="tjmk-profile-content-wrpper">

    <?php
    // if (isset($_GET['error_message'])) {
    //     echo '<div class="error">' . esc_html($_GET['error_message']) . '</div>';
    // }
    
    // Display the message from the transient, if it exists
    // if ($message = get_transient('form_submission_message')) {
    //     echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
    //     delete_transient('form_submission_message');
    // }
    

    ?>

    <h3><?php esc_html_e('Add New Profile', 'tjmk'); ?></h3>
    <div class="edit-options">
        <form method="post" name="createperson" id="frontend-profile-add" class="validate" novalidate="novalidate">

            <?php wp_nonce_field('frontend_add_profile_with_review_nonce'); ?>

            <input type="hidden" name="action" value="frontend_add_profile_with_review">

            <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

            <table class="form-table" role="presentation">
                <tbody>
                    <tr class="form-field">
                        <th scope="row"><label for="first_name"><?php esc_html_e('First Name', 'tjmk'); ?></label></th>
                        <td>
                            <input name="first_name" type="text" id="first_name" value="Nipa" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="last_name"><?php esc_html_e('Last Name', 'tjmk'); ?></label></th>
                        <td>
                            <input name="last_name" type="text" id="last_name" value="Islam" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="title"><?php esc_html_e('Professional Title', 'tjmk'); ?></label>
                        </th>
                        <td>
                            <input name="title" type="text" id="title" value="WordPress Developer" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="email"><?php esc_html_e('Email', 'tjmk'); ?></label></th>
                        <td>
                            <input name="email" type="email" id="email" value="tarikul@gmail.com" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="phone"><?php esc_html_e('Phone Number', 'tjmk'); ?></label></th>
                        <td>
                            <input name="phone" type="tel" id="phone" value="01752134658" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="address"><?php esc_html_e('Address', 'tjmk'); ?></label></th>
                        <td>
                            <input name="address" type="text" id="address" value="137 A Lorem Ipsum"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row">
                            <label for="zip_code"><?php esc_html_e('Zip Code', 'tjmk'); ?></label>
                        </th>
                        <td>
                            <input name="zip_code" type="text" id="zip_code" value="1204" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row">
                            <label for="city"><?php esc_html_e('City', 'tjmk'); ?>
                            </label>
                        </th>
                        <td>
                            <input name="city" type="text" id="city" value="Tongi" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="salary"><?php esc_html_e('Salary Per Month', 'tjmk'); ?></label>
                        </th>
                        <td>
                            <input name="salary_per_month" type="number" id="salary" value="10000000"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label
                                for="employee_type"><?php esc_html_e('Type of Employee', 'tjmk'); ?></label></th>
                        <td>
                            <input name="employee_type" type="text" id="employee_type" value="Freelancerss"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="region"><?php esc_html_e('Region', 'tjmk'); ?></label></th>
                        <td>
                            <input name="region" type="text" id="region" value="Region" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="state"><?php esc_html_e('State', 'tjmk'); ?></label></th>
                        <td>
                            <input name="state" type="text" id="state" value="Gazipur" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="country"><?php esc_html_e('Country', 'tjmk'); ?></label></th>
                        <td>
                            <input name="country" type="text" id="country" value="Bangladesh" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="municipality"><?php esc_html_e('Municipality', 'tjmk'); ?></label>
                        </th>
                        <td>
                            <input name="municipality" type="text" id="municipality" value="Municipality"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row">
                            <label for="department"><?php esc_html_e('Department', 'tjmk'); ?></label>
                        </th>
                        <td>
                            <input name="department" type="text" id="department" value="Department"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                </tbody>
            </table>

            <!-- Review Section  -->

            <div class="review-area">
                <div class="review-title">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Give reviews to %1$s?', 'tjmk'),
                            '<b>' . esc_html('The Profile') . '</b>'
                        );
                        ?>
                    </h2>

                </div>
                <div class="review-wrpper">

                    <div class="single-review ">
                        <h2>
                            <?php
                            printf(
                                esc_html__('Do you experience the official employee %1$s as impartial, factual, and fair? (from 1 to 5)', 'tjmk'),
                                '<b>' . esc_html('The Profile') . '</b>'
                            );
                            ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="fair" id="fair-impartial-input" value="1">
                    </div>

                    <div class="single-review ">
                        <h2>
                            <?php
                            printf(
                                esc_html__('Do you experience that the official employee %1$s has sufficient competence, is professional, and qualified for the given service and role? (from 1 to 5)', 'tjmk'),
                                '<b>' . esc_html('The Profile') . '</b>'
                            );
                            ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="professional" id="sufficient-competence-input" value="1">
                    </div>

                    <div class="single-review ">
                        <h2>
                            <?php
                            printf(
                                esc_html__('Do you experience that the official employee %1$s has given you a good response that you understand? (from 1 to 5)', 'tjmk'),
                                '<b>' . esc_html('The Profile') . '</b>'
                            );
                            ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="response" id="personal-response-input" value="1">
                    </div>

                    <div class="single-review ">
                        <h2>
                            <?php
                            printf(
                                esc_html__('Do you experience that the official employee %1$s has good communication skills and good response time? (from 1 to 5)', 'tjmk'),
                                '<b>' . esc_html('The Profile') . '</b>'
                            );
                            ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="communication" id="communication-skills-input" value="1">
                    </div>


                    <div class="single-review ">
                        <h2>
                            <?php
                            printf(
                                esc_html__('Do you experience that the official employee %1$s makes fair decisions? (from 1 to 5)', 'tjmk'),
                                '<b>' . esc_html('The Profile') . '</b>'
                            );
                            ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="decisions" id="fair-decisions-input" value="1">
                    </div>

                    <div class="single-review ">
                        <h2>
                            <?php echo esc_html__('Do you recommend this official employee? (from 1 to 5)', 'tjmk'); ?>
                        </h2>

                        <ul class="review-icon">
                            <li data-value="1" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="2" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="3" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="4" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                            <li data-value="5" class="star">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/icons/rating-two.png'; ?>"
                                    alt="">
                            </li>
                        </ul>
                        <input type="hidden" name="recommend" id="recommend-profile-input" value="1">
                    </div>
                </div>
            </div>
            <!-- Review Section  -->

            <!-- Comment Box -->
            <div class="comment-wrpper">
                <div class="inner-comment-wrpper">
                    <div class="title-box">
                        <h2><?php echo esc_html__('Do you recommend this official employee? (from 1 to 5)', 'tjmk'); ?>
                        </h2>
                    </div>
                    <div class="comment-form">
                        <textarea id="comments" name="comments"></textarea>
                    </div>
                </div>
            </div>


            <!-- Comment form -->
            <div class="submit-form">
                <p class="submit">
                    <input type="submit" name="createperson" id="createpersonsub" class="button button-primary"
                        value="<?php echo esc_attr__('Add Person Now', 'tjmk'); ?>">
                </p>
                <div id="form-message"></div>
            </div>
        </form>

    </div>
</div>
<?php
get_footer();
?>