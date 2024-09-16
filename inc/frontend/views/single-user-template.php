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

// Check if this is an edit form  edit-person&profile_id
$profile_id = (isset($_GET['profile_id']) && !empty($_GET['profile_id'])) ? $_GET['profile_id'] : false;
$db = Database::getInstance();
$person_data = $db->get_person_by_id($profile_id);
$is_review_exist = $db->get_existing_review($profile_id);

//echo "<pre>";

//print_r($person_data);
get_header();

?>
<!-- Main Content Area -->
<div class="tjmk-person-content-wrpper" style="padding-bottom: 50px;">
    <!-- Person Details Area -->
    <div class="need-border">
        <div class="inner-wrpper">
            <div class="person-details-wrpper">
                <div class="details-left-wrpper">
                    <!-- Person <?php echo PLUGIN_NAME_ASSETS_URI ?>/images Here -->
                    <div class="person-img-box">
                        <img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/person.svg" alt="">
                    </div>
                    <!-- Person Name Here -->
                    <div class="person-title">
                        <h3>What do others think of <span>sven nilsson</span> ? </h3>
                    </div>
                    <!-- Person Info Here -->
                    <div class="person-rating-wrpper">
                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/fair-impartial-icon-3.svg" alt="">
                            <h3>Is seen as fair and impartial</h3>
                        </div>

                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/sufficient-competence-2.svg" alt="">
                            <h3>Has sufficient competence and professionalism</h3>
                        </div>


                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/personal-response-4.svg" alt="">
                            <h3>Provides clear and understandable responses</h3>
                        </div>

                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/communication-skills-2.svg" alt="">
                            <h3>Has good communication skills and response time</h3>
                        </div>


                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/fair-decisions-3.svg" alt="">
                            <h3>Makes fair and wise decisions</h3>
                        </div>

                        <div class="scale-wrpper">
                            <img class="scale-reviews" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/recommend-person-5.svg" alt="">
                            <h3>Is recommended by others</h3>
                        </div>




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
                            <input type="text" value="Sven" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Last Name:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Nilsson" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Title:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Social Secretary" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Organization:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Kommun" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Administration:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Social services" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Municipality:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Göteborg" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Telephone:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="07382929732" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>E-post:</p>
                        </div>
                        <div class="detail-content-box">
                            <input type="text" value="Sven.nilsson@bastad.com" readonly>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                            <p>Rating:</p>
                        </div>
                        <div class="detail-content-box">
                            <ul class="detail-rating">
                                <li>
                                    <p>26 Reviews</p>
                                </li>
                                <li><img class="single-total-reivew" src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/single-total-reivew-3.svg"
                                        alt=""></li>

                            </ul>
                        </div>
                    </div>
                    <div class="single-details-wrpper">
                        <div class="tittle-box">
                        </div>
                        <div class="detail-content-box">
                            <div class="all-reviwe-btn">
                                <a href="" title="Buy all review report">Buy all review report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Review Area -->
    <div class="review-area">
        <div class="review-title">
            <h2>Give reviews to Sven Nilsson?</h2>
        </div>
        <div class="review-wrpper">

            <div class="single-review ">
                <h2>Do you experience the official employee Sven Nilsson as impartial, factual and fair?<br>(from 1 to
                    5)</h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="fair-impartial-input" id="fair-impartial-input" value="0">
            </div>

            <div class="single-review ">
                <h2>Do you experience that the official employee Sven Nilsson has sufficient competence, is professional
                    and qualified for the given service and role?<br>(from 1 to 5)</h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="sufficient-competence-input" id="sufficient-competence-input" value="0">
            </div>

            <div class="single-review ">
                <h2>Do you experience that the official employee Sven Nilsson have given you a good response that you
                    understand?<br>(from 1 to 5)</h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="personal-response-input" id="personal-response-input" value="0">
            </div>

            <div class="single-review ">
                <h2>Do you experience that the official employee Sven Nilsson have good communication skills and good
                    response time?<br>(from 1 to 5)</h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="communication-skills-input" id="communication-skills-input" value="0">
            </div>


            <div class="single-review ">
                <h2>Do you experience that the official employee Sven Nilsson makes fair decisions?<br>(from 1 to 5)
                </h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="fair-decisions-input" id="fair-decisions-input" value="0">
            </div>

            <div class="single-review ">
                <h2>Do you recommend this official employee?<br>(from 1 to 5)</h2>
                <ul class="review-icon">
                    <li data-value="1" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="2" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="3" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="4" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                    <li data-value="5" class="star"><img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/rating-two.svg" alt=""></li>
                </ul>
                <input type="hidden" name="recommend-person-input" id="recommend-person-input" value="0">
            </div>





        </div>
    </div>

    <!-- Comment Box -->
    <div class="comment-wrpper">
        <div class="inner-comment-wrpper">
            <div class="title-box">
                <h2>Say something about Sven nilsson</h2>
            </div>
            <div class="comment-form">
                <form action="" method="">
                    <textarea></textarea>
                    <input type="submit" value="Submit Now">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();