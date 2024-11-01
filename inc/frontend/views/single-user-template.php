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
use Tarikul\PersonsStore\Inc\Database\Database;
use Tarikul\PersonsStore\Inc\Helper\Helper;

// Check if this is an edit form  edit-person&profile_id
$profile_id = (isset($_GET['profile_id']) && !empty($_GET['profile_id'])) ? $_GET['profile_id'] : false;
$db = Database::getInstance();

// profile data 
$profile_data = $db->get_profile_by_id($profile_id);


// Check if user is logged in and retrieve user info
$user_info = Helper::get_current_user_id_and_roles();
$is_logged_in = !is_null($user_info);

// Set review existence status based on the user's review history for this profile
$is_review_exist = false;

if ($is_logged_in) {
    $is_review_exist = $db->get_existing_review($profile_id);
}

// echo "<pre>";
//print_r(is_wp_error($is_review_exist));
get_header();

?>
<!-- Main Content Area -->
<div class="tjmk-person-content-wrpper" style="padding-bottom: 50px;">
    <!-- Person Details Area -->
    <div class="need-border">
        <div class="inner-wrpper">
            <div class="person-details-wrpper">

                <div class="details-left-wrpper">
                    <!-- Profile images Here -->
                    <div class="person-img-box">
                        <img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/person.svg" alt="">
                    </div>
                    <!-- Person Name Here -->
                    <div class="person-title">
                        <h3>What do others think of
                            <span><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></span>
                            ?
                        </h3>
                    </div>
                    <!-- $average_rating = $this->db->get_average_meta_rating($profile_id, $key); -->

                    <!-- Person Info Here -->
                    <div class="person-rating-wrpper">

                        <?php
                        $criteria = [
                            'fair' => ['title' => 'IS SEEN AS FAIR AND IMPARTIAL', 'image' => 'fair-impartial-icon'],
                            'professional' => ['title' => 'HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'image' => 'sufficient-competence'],
                            'response' => ['title' => 'PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'image' => 'personal-response'],
                            'communication' => ['title' => 'HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'image' => 'communication-skills'],
                            'decisions' => ['title' => 'MAKES FAIR AND WISE DECISIONS', 'image' => 'fair-decisions'],
                            'recommend' => ['title' => 'IS RECOMMENDED BY OTHERS', 'image' => 'recommend-person'],
                        ];

                        // get all approves for the profile 
                        $approved_reviews = $db->get_reviews('approved', $profile_id);

                        foreach ($criteria as $key => $data) {
                            $average_rating = $db->get_average_meta_rating($profile_id, $key);
                            // Construct the image path
                            $image_path = PLUGIN_NAME_ASSETS_URI . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';
                            ?>
                            <div class="scale-wrpper">
                                <img class="scale-reviews" src="<?php echo esc_url($image_path) ?>" alt="">
                                <h3><?php esc_html_e($data['title'], 'tjmk'); ?></h3>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="person-profile">
                        <h2>if this is you, you can <br> claim this profile here</h2>
                    </div>

                    <div class="clame-btn">
                        <a href="" title="Click here for claim">Click here for claim</a>
                    </div>
                </div>

                <!-- Details Right Area -->
                <div class="details-right-area">
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>First name:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Last Name:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->last_name) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Title:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->title) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Department:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->department) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Type of Employee:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->employee_type) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Municipality:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->municipality) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Telephone:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->phone) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>E-post:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->email) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Rating:</p>
                        </div>
                        <div class="detail-content-box">
                            <ul class="detail-rating">
                                <li>
                                    <p>
                                        <?php
                                        $review_text = _n('Review', 'Reviews', count($approved_reviews), 'tjmk');
                                        // Display the average rating and the appropriate word
                                        echo esc_html(count($approved_reviews)) . ' ' . esc_html($review_text);
                                        ?>
                                    </p>
                                </li>
                                <li>
                                    <?php $image_path = PLUGIN_NAME_ASSETS_URI . '/images/icons/single-total-reivew' . '-' . $db->get_profile_average_rating($profile_id) . '.png'; ?>
                                    <img class="single-total-reivew" src="<?php echo esc_url($image_path); ?>"
                                        alt="single-total-reivew-<?php echo $db->get_profile_average_rating($profile_id) ?>">
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                        </div>
                        <div class="detail-content-box">
                            <div class="all-reviwe-btn">
                                <?php $cart_url = wc_get_cart_url() . "?add-to-cart=" . PRODUCT_ID . "&p_id=" . $profile_id; ?>
                                <a href="<?php echo esc_url($cart_url); ?>" title="Buy all review report">
                                    <?php esc_html_e('Buy all review report', 'tjmk') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Review Form  -->
    <form method="post" id="reviewform" class="validate" novalidate="novalidate">
        <?php wp_nonce_field('public_add_review_nonce'); ?>

        <input type="hidden" name="action" value="add_review">
        <input name="profile_id" type="hidden"
            value="<?php echo ($profile_data !== null) ? esc_html($profile_data->profile_id) : '' ?>">
        <!-- Review Area -->
        <div class="review-area">
            <div class="review-title">
                <h2>Give reviews to
                    <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b>?
                </h2>
            </div>
            <div class="review-wrpper">

                <div class="single-review ">
                    <h2>Do you experience the official employee
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b> as
                        impartial, factual and fair?<br>(from 1 to
                        5)
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="fair" id="fair-impartial-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>Do you experience that the official employee
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b> has
                        sufficient competence, is professional
                        and qualified for the given service and role?<br>(from 1 to 5)
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="professional" id="sufficient-competence-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>Do you experience that the official employee
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b> have
                        given you a good response that you
                        understand?<br>(from 1 to 5)
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="response" id="personal-response-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>Do you experience that the official employee
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b> have
                        good
                        communication skills and good
                        response time?<br>(from 1 to 5)
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="communication" id="communication-skills-input" value="0">
                </div>


                <div class="single-review ">
                    <h2>Do you experience that the official employee
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b> makes
                        fair decisions?<br>(from 1 to 5)
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="decisions" id="fair-decisions-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>Do you recommend this official employee?<br>(from 1 to 5)</h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="recommend" id="recommend-person-input" value="0">
                </div>
            </div>
        </div>

        <!-- Comment Box -->
        <div class="comment-wrpper">
            <div class="inner-comment-wrpper">
                <div class="title-box">
                    <h2>Say something about
                        <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b>
                    </h2>
                </div>
                <div class="comment-form">
                    <textarea name="comments"></textarea>
                </div>
            </div>
        </div>
        <div class="submit-form">
            <?php if (!$is_logged_in): ?>
                <!-- Show login prompt if user is not logged in -->
                <p>Please <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">log in</a> to submit a
                    review.</p>
            <?php elseif ($is_review_exist === true): ?>
                <!-- Show message if the user has already reviewed this profile -->
                <p>You have already reviewed this profile</p>
            <?php else: ?>
                <!-- Show the submit button if the user has not yet reviewed this profile -->
                <p class="submit">
                    <input type="submit" name="singleperson" id="singlereview" class="button button-primary"
                        value="Submit Review">
                </p>
            <?php endif; ?>

            <div id="user-review-form">
                <p id="review-message"></p>
            </div>
        </div>


    </form>

</div>
<?php
get_footer();