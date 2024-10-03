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
<div class="person-edit-page">

    <?php
    // Display the message from the transient, if it exists
    if ($message = get_transient('form_submission_message')) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
        delete_transient('form_submission_message');
    }
    ?>

    <h3>Add New Person</h3>

    <div class="edit-options">

        <form method="post" action="http://team.local/wp-admin/admin-post.php" name="createperson" id="createperson"
            class="validate" novalidate="novalidate">

            <?php wp_nonce_field('add_user_with_review_nonce'); ?>

            <input type="hidden" name="action" value="add_user_with_review">

            <input name="author_id" type="hidden" value="<?php echo get_current_user_id(); ?>">

            <table class="form-table" role="presentation">
                <tbody>

                    <tr class="form-field">
                        <th scope="row"><label for="first_name">First Name</label></th>
                        <td>
                            <input name="first_name" type="text" id="first_name" value="Raju" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="last_name">Last Name</label></th>
                        <td>
                            <input name="last_name" type="text" id="last_name" value="Ahmed" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="title">Title</label></th>
                        <td>
                            <input name="title" type="text" id="title" value="Developer" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="organization">Organization</label></th>
                        <td>
                            <input name="organization" type="text" id="organization" value="My Company"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="administration">Administration</label></th>
                        <td>
                            <input name="administration" type="text" id="administration" value="Online"
                                autocapitalize="none" autocorrect="off" autocomplete="off" required>
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
                        <th scope="row"><label for="phone">Phone</label></th>
                        <td>
                            <input name="phone" type="tel" id="phone" value="01752134658" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="email">Email</label></th>
                        <td>
                            <input name="email" type="email" id="email" value="raju2@gmail.com" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>


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
                            <input type="number" name="communication" id="communication" value="5">
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
                            <input type="number" name="recommend" id="recommend" value="2">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Say something about Sven nilsson</p>
                        </td>
                        <td>
                            <textarea name="comments" id="" cols="20" rows="4">Good</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="submit"><input type="submit" name="createperson" id="createpersonsub"
                    class="button button-primary" value="Add Person Now"></p>
        </form>

    </div>
</div>
<?php
get_footer();
?>