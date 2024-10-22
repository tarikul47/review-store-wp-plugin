<div class="person-edit-page">
    <div class="edit-options">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" name="reviewupdate"
            id="reviewupdate" class="validate" novalidate="novalidate">

            <?php wp_nonce_field('tjmk_review_update'); ?>

            <input type="hidden" name="action" value="tjmk_review_update">

            <input type="hidden" name="return_to" value="<?php echo esc_attr($_GET['return_to']); ?>">

            <input name="profile_id" type="hidden" value="<?php echo esc_html($_GET['profile_id']); ?>">

            <input name="review_id" type="hidden" value="<?php echo esc_html($_GET['review_id']); ?>">

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