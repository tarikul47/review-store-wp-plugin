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

    <h3>Add New Person</h3>

    <div class="edit-options">

        <form method="post" name="createperson" id="createperson" class="validate" novalidate="novalidate">

            <table class="form-table" role="presentation">
                <tbody>

                    <tr class="form-field">
                        <th scope="row"><label for="first_name">First Name</label></th>
                        <td>
                            <input name="first_name" type="text" id="first_name" value="" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="last_name">Last Name</label></th>
                        <td>
                            <input name="last_name" type="text" id="last_name" value="" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="title">Title</label></th>
                        <td>
                            <input name="title" type="text" id="title" value="" autocapitalize="none" autocorrect="off"
                                autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="organization">Organization</label></th>
                        <td>
                            <input name="organization" type="text" id="organization" value="" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="administration">Administration</label></th>
                        <td>
                            <input name="administration" type="text" id="administration" value="" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="municipality">Municipality</label></th>
                        <td>
                            <input name="municipality" type="text" id="municipality" value="" autocapitalize="none"
                                autocorrect="off" autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="phone">Phone</label></th>
                        <td>
                            <input name="phone" type="tel" id="phone" value="" autocapitalize="none" autocorrect="off"
                                autocomplete="off" required>
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th scope="row"><label for="email">Email</label></th>
                        <td>
                            <input name="email" type="email" id="email" value="" autocapitalize="none" autocorrect="off"
                                autocomplete="off" required>
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
                            <input type="number" name="fair" id="fair">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Do you feel that the official has sufficient competence, is professional and qualified
                                for his service (from 1 to 5)</p>
                        </td>
                        <td>
                            <input type="number" name="professional" id="professional">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Do you feel that the official has a personal and good response (from 1 to 5)</p>
                        </td>
                        <td>
                            <input type="number" name="response" id="response">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Do you feel that the official has good communication, good response time (from 1 to 5)
                            </p>
                        </td>
                        <td>
                            <input type="number" name="communication" id="communication">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Do you feel that the official makes fair decisions (from 1 to 5)</p>
                        </td>
                        <td>
                            <input type="number" name="decisions" id="decisions">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Do you recommend this official employee? (from 1 to 5)</p>
                        </td>
                        <td>
                            <input type="number" name="recommend" id="recommend">
                        </td>
                    </tr>

                    <tr class="form-field give-review">
                        <td>
                            <p>Say something about Sven nilsson</p>
                        </td>
                        <td>
                            <textarea name="" id="" cols="20" rows="4"></textarea>
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