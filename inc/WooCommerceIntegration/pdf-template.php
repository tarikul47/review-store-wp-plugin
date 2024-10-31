<!DOCTYPE html>
<html>

<head>
<<<<<<< HEAD
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="pdf-style.css" rel="stylesheet">


</head>

<body>
    <div class="header">
        <div class="logo-section">
            <a href="https://tjanstemannakollen.se"><img src="./images/logos-white.png" alt=""></a>
        </div>
        <div class="order-info">
            <div>Order Date: 2024-10-20</div>
            <div>Order ID: #8384829</div>
        </div>
    </div>

    <div class="profile-section">
        <div class="profile-image">
            <img src="./images/person.svg" alt="">
        </div>
        <div class="profile-info">
            <h2>Tarikul Islam</h2>
            <p>Tarikul Islam is a <strong>WordPress Developer</strong> working in <strong>Municipality</strong> in city
                <strong>Dhaka</strong>, in <strong>Bangladesh.</strong></p>

            <div class="overall-rating">
                <img class="" src="./images/icons/review-icon-4.svg" alt="">
                <span class="rating-count">26 TOTAL REVIEWS</span>
            </div>
            <div class="contact-person">
                <div class="contact-list">
                    <img src="./images/icons/email.png" alt="Email">
                    <span>tarikul@gmail.com</span>
                </div>
                <div class="contact-list">
                    <img src="./images/icons/phone.png" alt="Email">
                    <span>025 625 021 5501</span>
                </div>
            </div>
        </div>
    </div>

    <div class="ratings-section">
        <h3 class="section-title">Overall Ratings</h3>
        <div class="ratings-grid">
            <div class="rating-card">
                <div class="rating-title">IS SEEN AS FAIR AND IMPARTIAL</div>
                <img src="./images/icons/fair-impartial-icon-2.svg" alt="">
            </div>
            <div class="rating-card">
                <div class="rating-title">HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM</div>
                <img src="./images/icons/sufficient-competence-4.svg" alt="">
            </div>
            <div class="rating-card">
                <div class="rating-title">PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES</div>
                <img src="./images/icons/personal-response-4.svg" alt="">
            </div>
            <div class="rating-card">
                <div class="rating-title">HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME</div>
                <img src="./images/icons/communication-skills-3.svg" alt="">
            </div>
            <div class="rating-card">
                <div class="rating-title">MAKES FAIR AND WISE DECISIONS</div>
                <img src="./images/icons/fair-decisions-4.svg" alt="">
            </div>
            <div class="rating-card">
                <div class="rating-title">IS RECOMMENDED BY OTHERS</div>
                <img src="./images/icons/recommend-person-5.svg" alt="">
            </div>
        </div>

        <h3 class="section-title">All Reviews (26)</h3>
        <div class="review-card">
            <div class="review-meta">
                <span>Reviewed By</span>: Anonymous | <span>Date</span>: 2024-10-20 | <span>ID</span>: 39227
            </div>
            <div class="ratings-grid">
                <div class="rating-card">
                    <div class="rating-title">IS SEEN AS FAIR AND IMPARTIAL</div>
                    <img src="./images/icons/fair-impartial-icon-2.svg" alt="">
                </div>
                <div class="rating-card">
                    <div class="rating-title">HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM</div>
                    <img src="./images/icons/sufficient-competence-4.svg" alt="">
                </div>
                <div class="rating-card">
                    <div class="rating-title">PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES</div>
                    <img src="./images/icons/personal-response-4.svg" alt="">
                </div>
                <div class="rating-card">
                    <div class="rating-title">HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME</div>
                    <img src="./images/icons/communication-skills-3.svg" alt="">
                </div>
                <div class="rating-card">
                    <div class="rating-title">MAKES FAIR AND WISE DECISIONS</div>
                    <img src="./images/icons/fair-decisions-4.svg" alt="">
                </div>
                <div class="rating-card">
                    <div class="rating-title">IS RECOMMENDED BY OTHERS</div>
                    <img src="./images/icons/recommend-person-5.svg" alt="">
                </div>
            </div>
            <div class="review-comment">
                <p class="heading-comment">Comment</p>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                    totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                    dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,
                    sed quia.</p>
            </div>
        </div>
    </div>

    <footer>
        © 2024 Tjanstemannakollen - Alla rattigheter forbehallna.
    </footer>
=======
    <meta charset="UTF-8">
    <title>Review Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .header {
            background-color: #003366;
            color: #fff;
            padding: 10px 20px;
            text-align: right;
            font-size: 14px;
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

        .review-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 35px;
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
            margin-bottom: 5px;
        }

        .score {
            font-size: 16px;
            color: #4CAF50;
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <span>Order Date: <?php esc_html_e($formatted_order_date, 'tjmk') ?></span><br>
            <span>Order ID: #<?php esc_html_e($order_id, 'tjmk') ?></span>
        </div>

        <!-- Profile Section -->
        <div class="profile">
            <img src="profile.jpg" alt="Profile Image">
            <h2><?php esc_html_e($profile_user_name, 'tjmk') ?></h2>

            <!-- Tarikul Islam is a WordPress Developer working in Municipality in city Dhaka, in Bangladesh. -->

            <p>
                <?php esc_html_e($profile_user_name, 'tjmk') ?> is a
                <strong><?php esc_html_e($profile_data->title, 'tjmk') ?></strong> working in
                <strong><?php esc_html_e($profile_data->municipality, 'tjmk') ?></strong> in city
                <strong><?php esc_html_e($profile_data->state, 'tjmk') ?></strong>, in
                <strong><?php esc_html_e($profile_data->country, 'tjmk') ?></strong>.
            </p>

            <div class="info">
                <span class="icon green">📞</span>
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
                // Define the criteria keys and their labels
                $criteria = [
                    'fair' => 'IS SEEN AS FAIR AND IMPARTIAL',
                    'professional' => 'HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM',
                    'response' => 'PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES',
                    'communication' => 'HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME',
                    'decisions' => 'MAKES FAIR AND WISE DECISIONS',
                    'recommend' => 'IS RECOMMENDED BY OTHERS',
                ];

                // Split criteria into two sets for two rows
                $first_row_criteria = array_slice($criteria, 0, 3);
                $second_row_criteria = array_slice($criteria, 3, 3);

                // Helper function to generate rating icons based on average score
                function get_rating_icons($average_rating)
                {
                    $icons = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $color = ($i <= $average_rating) ? 'green' : 'red';
                        $icon = ($color === 'green') ? '🟢' : '🔴';
                        $icons .= "<span class='icon $color'>$icon</span> ";
                    }
                    return $icons;
                }

                // Render the first row
                echo '<tr>';
                foreach ($first_row_criteria as $key => $label) {
                    $average_rating = $this->db->get_average_meta_rating($profile_id, $key);
                    echo '<td class="rating-box">';
                    echo '<div class="criteria">' . $label . '</div>';
                    echo '<div class="score">' . $average_rating . ' ' . get_rating_icons($average_rating) . '</div>';
                    echo '</td>';
                }
                echo '</tr>';

                // Render the second row
                echo '<tr>';
                foreach ($second_row_criteria as $key => $label) {
                    $average_rating = $this->db->get_average_meta_rating($profile_id, $key);
                    echo '<td class="rating-box">';
                    echo '<div class="criteria">' . $label . '</div>';
                    echo '<div class="score">' . get_rating_icons($average_rating) . '</div>';
                    echo '</td>';
                }
                echo '</tr>';
                ?>
            </table>
        </div>

        <!-- Reviews Section -->
        <div class="ratings">
            <div class="ratings-title">All Reviews (<?php echo count($approved_reviews); ?>)</div>

            <?php foreach ($approved_reviews as $review): ?>
                <div class="review-box">
                    <div class="title">
                        Reviewed By: Anonymous | Date: <?php echo date('Y-m-d', strtotime($review['created_at'])); ?> | ID:
                        <?php echo $review['review_id']; ?>
                    </div>
                    <table class="ratings-table">
                        <tr>
                            <td class="rating-box">
                                <div class="criteria">IS SEEN AS FAIR AND IMPARTIAL</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['fair'] ?? 0);
                                    ?>
                                </div>
                            </td>
                            <td class="rating-box">
                                <div class="criteria">HAS SUFFICIENT COMPETENCE AND PROFESSIONALISM</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['professional'] ?? 0);
                                    ?>
                                </div>
                            </td>
                            <td class="rating-box">
                                <div class="criteria">PROVIDES CLEAR AND UNDERSTANDABLE RESPONSES</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['response'] ?? 0);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="rating-box">
                                <div class="criteria">HAS GOOD COMMUNICATION SKILLS AND RESPONSE TIME</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['communication'] ?? 0);
                                    ?>
                                </div>
                            </td>
                            <td class="rating-box">
                                <div class="criteria">MAKES FAIR AND WISE DECISIONS</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['decisions'] ?? 0);
                                    ?>
                                </div>
                            </td>
                            <td class="rating-box">
                                <div class="criteria">IS RECOMMENDED BY OTHERS</div>
                                <div class="score">
                                    <?php
                                    echo get_rating_icons($review['meta']['recommend'] ?? 0);
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="comemnt-box">
                        <div class="title">COMMENT</div>
                        <p><?php echo htmlspecialchars($review['meta']['comment'] ?? 'Comment text goes here.'); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer -->
        <div class="footer">
            © 2024 Tjanstemannakollen - Alla rättigheter förbehållna.
        </div>
    </div>
>>>>>>> d751beb9b9de24bcdb82320ddcae68c1644763e8
</body>

</html>