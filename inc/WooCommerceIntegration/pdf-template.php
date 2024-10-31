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
                                <img src="<?php echo PLUGIN_NAME_ASSETS_URI . '/images/logos-white.png' ?>" alt=""
                                    style="width: 138px; height: auto;">
                            </a>
                        </td>
                        <td class="order-date">
                            <span>Order Date: <?php esc_html_e($formatted_order_date, 'tjmk') ?></span><br>
                            <span>Order ID: #<?php esc_html_e($order_id, 'tjmk') ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Profile Section -->
        <div class="profile">
            <h2><?php esc_html_e($profile_user_name, 'tjmk') ?></h2>
            <p>
                <?php esc_html_e($profile_user_name, 'tjmk') ?> is a
                <strong><?php esc_html_e($profile_data->title, 'tjmk') ?></strong> working in
                <strong><?php esc_html_e($profile_data->municipality, 'tjmk') ?></strong> in city
                <strong><?php esc_html_e($profile_data->state, 'tjmk') ?></strong>, in
                <strong><?php esc_html_e($profile_data->country, 'tjmk') ?></strong>.
            </p>

            <div class="info">
                <?php esc_html_e($profile_data->email, 'tjmk') ?> |
                <?php esc_html_e($profile_data->phone, 'tjmk') ?>
            </div>
            <div class="info">
                <strong><?php esc_html_e(count($approved_reviews), 'tjmk') ?> TOTAL REVIEWS</strong>
            </div>
        </div>

        <!-- Overall Ratings -->
        <div class="ratings">
            <div class="ratings-title">Overall Ratings</div>
            <table class="ratings-table">
                <?php
                $criteria = [
                    'fair' => ['title' => 'IS SEEN AS FAIR AND IMPARTIAL', 'image' => 'fair-impartial-icon'],
                    'professional' => ['title' => 'HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'image' => 'sufficient-competence'],
                    'response' => ['title' => 'PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'image' => 'personal-response'],
                    'communication' => ['title' => 'HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'image' => 'communication-skills'],
                    'decisions' => ['title' => 'MAKES FAIR AND WISE DECISIONS', 'image' => 'fair-decisions'],
                    'recommend' => ['title' => 'IS RECOMMENDED BY OTHERS', 'image' => 'recommend-person'],
                ];

                // Split criteria into two sets for two rows
                $first_row_criteria = array_slice($criteria, 0, 3);
                $second_row_criteria = array_slice($criteria, 3, 3);

                // Render the first row
                echo '<tr>';
                foreach ($first_row_criteria as $key => $data) {
                    $average_rating = $this->db->get_average_meta_rating($profile_id, $key);

                    // Construct the image path
                    $image_path = PLUGIN_NAME_ASSETS_URI . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';
                    //  $image_path = PLUGIN_NAME_ASSETS_URI . '/images/icons/communication-skills-1.png';
                
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
                    $image_path = PLUGIN_NAME_ASSETS_URI . '/images/icons/' . $data['image'] . '-' . $average_rating . '.png';

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
            <div class="ratings-title">All Reviews (<?php echo count($approved_reviews); ?>)</div>

            <?php
            // Function to get image path based on rating
            function get_image_path($image_base, $rating)
            {
                return PLUGIN_NAME_ASSETS_URI . '/images/icons/' . $image_base . '-' . $rating . '.png';
            }
            foreach ($approved_reviews as $review): ?>
                <div class="review-box">
                    <div class="title">
                        Reviewed By: Anonymous | Date: <?php echo date('Y-m-d', strtotime($review['created_at'])); ?> |
                        ID: <?php echo $review['review_id']; ?>
                    </div>
                    <table class="ratings-table">
                        <?php
                        // Define the criteria keys and their labels and icons
                        $criteria = [
                            'fair' => ['title' => 'IS SEEN AS FAIR AND IMPARTIAL', 'image' => 'fair-impartial-icon'],
                            'professional' => ['title' => 'HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM', 'image' => 'sufficient-competence'],
                            'response' => ['title' => 'PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES', 'image' => 'personal-response'],
                            'communication' => ['title' => 'HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME', 'image' => 'communication-skills'],
                            'decisions' => ['title' => 'MAKES FAIR AND WISE DECISIONS', 'image' => 'fair-decisions'],
                            'recommend' => ['title' => 'IS RECOMMENDED BY OTHERS', 'image' => 'recommend-person'],
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
                        <div class="title">COMMENT</div>
                        <p><?php echo htmlspecialchars($review['meta']['comments'] ?? 'Comment text goes here.'); ?></p>
                    </div>
                </div>
                <?php
                //error_log(print_r($review, true));
            endforeach; ?>
        </div>


        <!-- Footer -->
        <div class="footer">
            © 2024 Tjanstemannakollen - Alla rättigheter förbehållna.
        </div>
    </div>
</body>

</html>