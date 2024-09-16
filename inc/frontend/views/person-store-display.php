<?php
use Tarikul\PersonsStore\Inc\Database\Database;

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */
get_header();
$db = Database::getInstance();
$users = $db->get_users_with_review_data();
//echo "<pre>";
//print_r($users);
?>
<div class="tjmk-search-result-wrpper">
    <!-- search result area -->
    <div class="search-table-wrpper">
        <!-- filter buttons on table top -->
        <div class="top-wrpper">
            <!-- Search Box -->
            <div class="search-input-wrpper">
                <img src="images/icons/search-icon.svg" alt="">
                <input type="search" placeholder="Search...">
            </div>
        </div>
        <!-- search table one -->
        <div class="result-shown-table">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>First Name</th>
                        <th> Last Name</th>
                        <th>Title</th>
                        <th>Type of Employee</th>
                        <th>Department</th>
                        <th>Municipality</th>
                        <th>Rating</th>
                        <th>Buy report</th>
                        <th>View</th>
                    </tr>

                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr id="post-id-<?php echo esc_attr($user->profile_id); ?>">
                                <td><?php echo esc_html($user->first_name); ?></td>
                                <td><?php echo esc_html($user->last_name); ?></td>
                                <td><?php echo esc_html($user->title); ?></td>
                                <td><?php echo esc_html($user->employee_type); ?></td>
                                <td><?php echo esc_html($user->department); ?></td>
                                <td><?php echo esc_html($user->municipality); ?></td>
                                <td>
                                    <img class="review-score-icon" date-rating="<?php echo esc_html($user->average_rating); ?>"
                                        src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/review-icon-4.svg" alt="">
                                </td>
                                <td>
                                    <a id="<?php echo esc_attr($user->profile_id); ?>" class="buy-review" href="">
                                        <img src="<?php echo PLUGIN_NAME_ASSETS_URI ?>/images/icons/card-icon.svg" alt="">
                                    </a>
                                </td>
                                <td>
                                    <a target="_blank" id="<?php echo esc_attr($user->profile_id); ?>" class=""
                                        href="<?php echo esc_url(get_permalink() . '?profile_id=' . $user->profile_id); ?>">Click
                                        Here</a>
                                </td>
                            <tr></tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <p class="no-person-found"><?php _e('No matching data found.', 'text-domain'); ?> <a
                                        href=""><?php _e('Add a person data here.', 'text-domain'); ?></a></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tr>
                </table>
                <div class="pagination">
                    <ul>
                        <li><a href="">Previous</a></li>
                        <li><a id="active" href="">1</a></li>
                        <li><a href="">2</a></li>
                        <li><a href="">3</a></li>
                        <li><a href="">Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <h2>This is if no person found</h2>
        <div class="result-shown-table"></div>
        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>First Name</th>
                    <th>
                        <p><span>Last Name</span></p>
                    </th>
                    <th>
                        <p><span>Title</span></p>
                    </th>
                    <th>
                        <p><span>Organisation</span></p>
                    </th>
                    <th>
                        <p><span>Administration</span></p>
                    </th>
                    <th>Municipality</th>
                    <th>
                        <p><span>Rating</span></p>
                    </th>
                    <th>Buy report</th>
                </tr>
                <tr>
                    <td colspan="8">
                        <p class="no-person-found">No matching data found. <a href="">Add a person data here</a></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>
<?php
get_footer();