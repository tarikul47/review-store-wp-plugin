<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Review Summary</title>
    <style>
        @font-face {
            font-family: 'Roboto';
            src: url('Roboto-Regular.ttf') format('truetype');
            font-weight: 400;
            /* Regular weight */
            font-style: normal;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('Roboto-Medium.ttf') format('truetype');
            font-weight: 500;
            /* Medium weight */
            font-style: normal;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('Roboto-Bold.ttf') format('truetype');
            font-weight: 700;
            /* Bold weight */
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {

            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;

        }

        .header {
            background-color: #003366;
            color: #fff;
            padding: 10px 20px;
            text-align: right;
            font-size: 14px;
        }

        .header table {
            width: 100%;
        }

        .header td.logo {
            text-align: left;
        }

        .header td.order-date {
            text-align: right;
            color: #ffffff;
        }

        td.logo img {
            width: 138px;
            height: auto;
            /* Ensure aspect ratio is maintained */
        }

        .profile {
            text-align: center;
            margin: 20px 0;
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .profile h2 {
            margin: 5px 0;
            font-size: 24px;
        }

        .profile p {
            font-size: 14px;
            color: #666;
        }

        .profile .info {
            font-size: 14px;
            color: #333;
            margin: 10px 0;
        }

        .ratings,
        .reviews {
            margin: 20px 0;
        }

        .ratings-title,
        .reviews-title {
            font-size: 18px;
            font-weight: bold;
        }

        .ratings-table {
            width: 100%;
            border-spacing: 10px;
        }

        .rating-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .criteria {
            font-size: 14px;
            font-weight: bold;
        }

        .score {
            font-size: 16px;
            color: #4CAF50;
        }

        .score img {
            width: 130px;
        }

        .comemnt-box .title {
            font-weight: bold;
            /* padding: 10px 0px; */
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            background-color: #003366;
            color: #fff;
            margin-top: 20px;
        }

        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            line-height: 24px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .red {
            background-color: #f44336;
        }

        .green {
            background-color: #4CAF50;
        }

        .review-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 35px;
            margin-top: 20px;
        }

        .reviews-title {
            padding: 15px 0px;
        }

        .comemnt-box {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table>
                <tbody>
                    <tr>
                        <td class="logo">
                            <a href="https://tjanstemannakollen.se">
                                <img src="<?php echo TJMK_PLUGIN_ASSETS_URL . '/images/logos-white.png' ?>" alt=""
                                    style="width: 138px; height: auto;">
                            </a>
                        </td>
                        <td class="order-date">
                            <span><?php esc_html_e('Order Date:', 'tjmk'); ?>
                                <?php esc_html_e($formatted_order_date, 'tjmk') ?></span><br>
                            <span><?php esc_html_e('Order ID:', 'tjmk'); ?>
                                #<?php esc_html_e($order_id, 'tjmk') ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Profile Section -->
        <div class="profile">
            <h2><?php echo esc_html($profile_user_name); ?></h2>
            <p>
                <?php echo esc_html($profile_user_name); ?> <?php esc_html_e('is a', 'tjmk'); ?>
                <strong><?php echo esc_html($profile_data->title); ?></strong>
                <?php esc_html_e('working in', 'tjmk'); ?>
                <strong><?php echo esc_html($profile_data->municipality); ?></strong>
                <?php esc_html_e('in city', 'tjmk'); ?>
                <strong><?php echo esc_html($profile_data->state); ?></strong>, <?php esc_html_e('in', 'tjmk'); ?>
                <strong><?php echo esc_html($profile_data->country); ?></strong>.
            </p>

            <div class="info">
                <?php echo esc_html($profile_data->email); ?> |
                <?php echo esc_html($profile_data->phone); ?>
            </div>
            <div class="info">
                <strong><?php echo esc_html(count($approved_reviews)) . ' ' . __('TOTAL REVIEWS', 'tjmk'); ?></strong>
            </div>
        </div>

        <!-- Overall Ratings -->
        <div class="ratings">
            <div class="ratings-title"><?php esc_html_e('Overall Ratings', 'tjmk'); ?></div>
            <table class="ratings-table">
                <?php
                $criteria = [
                    'fair' => ['title' => __('IS SEEN AS FAIR AND IMPARTIAL', 'tjmk'), 'image' => 'fair-impartial-icon'],
                    'professional' => ['title' => __('HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'tjmk'), 'image' => 'sufficient-competence'],
                    'response' => ['title' => __('PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'tjmk'), 'image' => 'personal-response'],
                    'communication' => ['title' => __('HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'tjmk'), 'image' => 'communication-skills'],
                    'decisions' => ['title' => __('MAKES FAIR AND WISE DECISIONS', 'tjmk'), 'image' => 'fair-decisions'],
                    'recommend' => ['title' => __('IS RECOMMENDED BY OTHERS', 'tjmk'), 'image' => 'recommend-profile'],
                ];

                // Split criteria into two sets for two rows
                $first_row_criteria = array_slice($criteria, 0, 3);
                $second_row_criteria = array_slice($criteria, 3, 3);

                // Render the first row
                echo '<tr>';
                foreach ($first_row_criteria as $key => $data) {
                    $average_rating = $this->db->get_average_meta_rating($profile_id, $key);

                    // Construct the image path
                    $image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';
                    //  $image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/communication-skills-1.png';
                
                    // Log image path and rating to the error log for debugging
                    //   error_log("Criterion: $key, Rating: $average_rating, Image Path: $image_path");
                
                    // Display the image in the table
                    echo '<td class="rating-box">';
                    echo '<div style="padding-bottom: 50px;" class="criteria">' . esc_html($data['title']) . '</div>';
                    echo '<div class="score"><img style="width: 130px; height: auto;" src="' . esc_url($image_path) . '" alt=""></div>';
                    echo '</td>';
                }
                echo '</tr>';

                // Render the second row
                echo '<tr>';
                foreach ($second_row_criteria as $key => $data) {
                    $average_rating = $this->db->get_average_meta_rating($profile_id, $key);

                    // Construct the image path
                    $image_path = TJMK_PLUGIN_ASSETS_URL . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';

                    // Log image path and rating to the error log for debugging
                    //  error_log("Criterion: $key, Rating: $average_rating, Image Path: $image_path");
                
                    // Display the image in the table
                    echo '<td class="rating-box">';
                    echo '<div class="criteria">' . esc_html($data['title']) . '</div>';
                    echo '<div class="score"><img style="width: 130px; height: auto;" src="' . esc_url($image_path) . '" alt=""></div>';
                    echo '</td>';
                }
                echo '</tr>';
                ?>
            </table>

        </div>


        <!-- Reviews Section -->
        <div class="ratings">
            <div class="ratings-title"><?php echo __('All Reviews', 'tjmk') . ' (' . count($approved_reviews) . ')'; ?>
            </div>

            <?php
            // Function to get image path based on rating
            function get_image_path($image_base, $rating)
            {
                return TJMK_PLUGIN_ASSETS_URL . '/images/icons/' . $image_base . '-' . $rating . '.png';
            }
            foreach ($approved_reviews as $review): ?>
                <div class="review-box">
                    <div class="title">
                        <?php esc_html_e('Reviewed By:', 'tjmk'); ?>     <?php esc_html_e('Anonymous', 'tjmk'); ?> |
                        <?php esc_html_e('Date:', 'tjmk'); ?>
                        <?php echo esc_html(date('Y-m-d', strtotime($review['created_at']))); ?> |
                        <?php esc_html_e('ID:', 'tjmk'); ?>     <?php echo esc_html($review['review_id']); ?>
                    </div>
                    <table class="ratings-table">
                        <?php
                        // Define the criteria keys and their labels and icons
                        $criteria = [
                            'fair' => ['title' => __('IS SEEN AS FAIR AND IMPARTIAL', 'tjmk'), 'image' => 'fair-impartial-icon'],
                            'professional' => ['title' => __('HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'tjmk'), 'image' => 'sufficient-competence'],
                            'response' => ['title' => __('PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'tjmk'), 'image' => 'personal-response'],
                            'communication' => ['title' => __('HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'tjmk'), 'image' => 'communication-skills'],
                            'decisions' => ['title' => __('MAKES FAIR AND WISE DECISIONS', 'tjmk'), 'image' => 'fair-decisions'],
                            'recommend' => ['title' => __('IS RECOMMENDED BY OTHERS', 'tjmk'), 'image' => 'recommend-profile'],
                        ];

                        // Split the criteria into two rows
                        $first_row_criteria = array_slice($criteria, 0, 3);
                        $second_row_criteria = array_slice($criteria, 3, 3);

                        // Render the first row
                        echo '<tr>';
                        foreach ($first_row_criteria as $key => $data) {
                            $average_rating = $review['meta'][$key] ?? 0; // Get the rating value
                            $image_path = get_image_path($data['image'], $average_rating); // Construct the image path
                            echo '<td class="rating-box">';
                            echo '<div class="criteria">' . $data['title'] . '</div>';
                            echo '<div class="score">';
                            //   echo get_rating_icons($average_rating); // Display rating icons if needed
                            echo '<img style="width: 130px; height: auto;" src="' . esc_url($image_path) . '" alt="">';
                            echo '</div>';
                            echo '</td>';
                        }
                        echo '</tr>';

                        // Render the second row
                        echo '<tr>';
                        foreach ($second_row_criteria as $key => $data) {
                            $average_rating = $review['meta'][$key] ?? 0; // Get the rating value
                            $image_path = get_image_path($data['image'], $average_rating); // Construct the image path
                            echo '<td class="rating-box">';
                            echo '<div class="criteria">' . $data['title'] . '</div>';
                            echo '<div class="score">';
                            //   echo get_rating_icons($average_rating); // Display rating icons if needed
                            echo '<img style="width: 130px; height: auto;" src="' . esc_url($image_path) . '" alt="">';
                            echo '</div>';
                            echo '</td>';
                        }
                        echo '</tr>';
                        ?>
                    </table>
                    <div class="comemnt-box">
                        <div class="title"><?php esc_html_e('COMMENT', 'tjmk'); ?></div>
                        <p><?php echo esc_html($review['meta']['comments'] ?? __('Comment text goes here.', 'tjmk')); ?></p>
                    </div>
                </div>
                <?php
                //error_log(print_r($review, true));
            endforeach; ?>
        </div>


        <!-- Footer -->
        <div class="footer">
            © 2024 Tjanstemannakollen - <?php esc_html_e('All rights reserved.', 'tjmk'); ?>
        </div>
    </div>
</body>

</html>