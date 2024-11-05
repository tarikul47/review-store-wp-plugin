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
use Tarikul\TJMK\Inc\Database\Database;
use Tarikul\TJMK\Inc\Helper\Helper;

// Check if this is an edit form  edit-profile&profile_id
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
<div class="tjmk-profile-content-wrpper" style="padding-bottom: 50px;">
    <!-- Person Details Area -->
    <div class="need-border">
        <div class="inner-wrpper">
            <div class="profile-details-wrpper">

                <div class="details-left-wrpper">
                    <!-- Profile images Here -->
                    <div class="profile-img-box">
                        <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/profile.svg" alt="">
                    </div>
                    <!-- Person Name Here -->
                    <div class="profile-title">
                        <h3><?php esc_html_e('What do others think of', 'tjmk'); ?>
                            <span><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></span>
                            ?
                        </h3>
                    </div>
                    <!-- $average_rating = $this->db->get_average_meta_rating($profile_id, $key); -->

                    <!-- Person Info Here -->
                    <div class="profile-rating-wrpper">

                        <?php
                        $criteria = [
                            'fair' => [
                                'title' => __('IS SEEN AS FAIR AND IMPARTIAL', 'tjmk'),
                                'image' => 'fair-impartial-icon'
                            ],
                            'professional' => [
                                'title' => __('HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'tjmk'),
                                'image' => 'sufficient-competence'
                            ],
                            'response' => [
                                'title' => __('PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'tjmk'),
                                'image' => 'personal-response'
                            ],
                            'communication' => [
                                'title' => __('HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'tjmk'),
                                'image' => 'communication-skills'
                            ],
                            'decisions' => [
                                'title' => __('MAKES FAIR AND WISE DECISIONS', 'tjmk'),
                                'image' => 'fair-decisions'
                            ],
                            'recommend' => [
                                'title' => __('IS RECOMMENDED BY OTHERS', 'tjmk'),
                                'image' => 'recommend-profile'
                            ],
                        ];


                        // get all approves for the profile 
                        $approved_reviews = $db->get_reviews('approved', $profile_id);

                        foreach ($criteria as $key => $data) {
                            $average_rating = $db->get_average_meta_rating($profile_id, $key);
                            // Construct the image path
                            $image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';
                            ?>
                            <div class="scale-wrpper">
                                <img class="scale-reviews" src="<?php echo esc_url($image_path) ?>" alt="">
                                <h3><?php esc_html_e($data['title'], 'tjmk'); ?></h3>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="profile-profile">
                        <h2>
                            <?php esc_html_e('If this is you, you can', 'tjmk'); ?>
                            <br>
                            <?php esc_html_e('claim this profile here', 'tjmk'); ?>
                        </h2>
                    </div>

                    <div class="clame-btn">
                        <a href=""
                            title="<?php esc_attr_e('Click here for claim', 'tjmk'); ?>"><?php esc_html_e('Click here for claim', 'tjmk'); ?></a>
                    </div>
                </div>

                <!-- Details Right Area -->
                <div class="details-right-area">
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('First name:', 'tjmk'); ?> </p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Last Name:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->last_name) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Title:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->title) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Department:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->department) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Type of Employee:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->employee_type) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Municipality:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->municipality) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Telephone:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->phone) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('E-post:', 'tjmk'); ?></p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text"
                                value="<?php echo ($profile_data !== null) ? esc_html($profile_data->email) : '' ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p><?php esc_html_e('Rating:', 'tjmk'); ?></p>
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
                                    <?php $image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/single-total-reivew' . '-' . $db->get_profile_average_rating($profile_id) . '.png'; ?>
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
                                <?php $cart_url = wc_get_cart_url() . "?add-to-cart=" . TJMK_PRODUCT_ID . "&p_id=" . $profile_id; ?>
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
                <h2><?php esc_html_e('Give reviews to', 'tjmk'); ?>
                    <b><?php echo ($profile_data !== null) ? esc_html($profile_data->first_name) : '' ?></b>?
                </h2>
            </div>
            <div class="review-wrpper">

                <div class="single-review ">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Do you experience the official employee %1$s as impartial, factual, and fair? (from 1 to 5)', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
                    </h2>

                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="fair" id="fair-impartial-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Do you experience that the official employee %1$s has sufficient competence, is professional and qualified for the given service and role? (from 1 to 5)', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
                    </h2>

                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="professional" id="sufficient-competence-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Do you experience that the official employee %1$s has given you a good response that you understand? (from 1 to 5)', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
                    </h2>
                    <ul class="review-icon">
                        <li data-value="1" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="2" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="3" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="4" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                        <li data-value="5" class="star"><img
                                src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt=""></li>
                    </ul>
                    <input type="hidden" name="response" id="personal-response-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Do you experience that the official employee %1$s has good communication skills and good response time? (from 1 to 5)', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
                    </h2>

                    <ul class="review-icon">
                        <li data-value="1" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="2" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="3" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="4" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="5" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                    </ul>
                    <input type="hidden" name="communication" id="communication-skills-input" value="0">
                </div>


                <div class="single-review ">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Do you experience that the official employee %1$s makes fair decisions? (from 1 to 5)', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
                    </h2>

                    <ul class="review-icon">
                        <li data-value="1" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="2" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="3" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="4" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="5" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                    </ul>
                    <input type="hidden" name="decisions" id="fair-decisions-input" value="0">
                </div>

                <div class="single-review ">
                    <h2>
                        <?php echo esc_html__('Do you recommend this official employee? (from 1 to 5)', 'tjmk'); ?>
                    </h2>

                    <ul class="review-icon">
                        <li data-value="1" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="2" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="3" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="4" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                        <li data-value="5" class="star">
                            <img src="<?php echo TJMK_PLUGIN_ASSETS_URL ?>/images/icons/rating-two.png" alt="">
                        </li>
                    </ul>
                    <input type="hidden" name="recommend" id="recommend-profile-input" value="0">
                </div>
            </div>
        </div>

        <!-- Comment Box -->
        <div class="comment-wrpper">
            <div class="inner-comment-wrpper">
                <div class="title-box">
                    <h2>
                        <?php
                        printf(
                            esc_html__('Say something about %1$s', 'tjmk'),
                            '<b>' . esc_html(($profile_data !== null) ? $profile_data->first_name : '') . '</b>'
                        );
                        ?>
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
                <p>
                    <?php
                    printf(
                        esc_html__('Please <a href="%s">log in</a> to submit a review.', 'tjmk'),
                        esc_url(wc_get_page_permalink('myaccount'))
                    );
                    ?>
                </p>
            <?php elseif ($is_review_exist === true): ?>
                <!-- Show message if the user has already reviewed this profile -->
                <p><?php echo esc_html__('You have already reviewed this profile', 'tjmk'); ?></p>

            <?php else: ?>
                <!-- Show the submit button if the user has not yet reviewed this profile -->
                <p class="submit">
                    <input type="submit" name="singleperson" id="singlereview" class="button button-primary"
                        value="<?php echo esc_attr__('Submit Review', 'tjmk'); ?>">
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