<!DOCTYPE html>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="pdf-style.css" rel="stylesheet"> -->

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
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 0px 15px;
        }

        .header {
            background-color: #1a237e;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            gap: 5px;
        }

        .logo-section img {
            width: 134px;
        }

        .order-info {
            font-size: 14px;
            text-align: right;
            font-weight: 400;
        }

        .profile-section {
            display: flex;
            gap: 15px;
            padding: 20px 0;
        }

        .profile-image img {
            width: 100px;
            height: auto;
        }


        .profile-info h2 {
            font-size: 20px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .profile-info p {
            font-weight: 400;
        }

        .rating-count {
            color: #666;
            margin-left: 10px;
            font-weight: 500;
        }

        .overall-rating {
            display: flex;
            gap: 5px;
            margin-top: 15px;
            align-items: center;
        }

        .overall-rating img {
            width: 142px;
        }

        .contact-person {
            margin-top: 8px;
            display: inline-flex;
        }

        .contact-list {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .contact-person img {
            width: 18px;
            margin-right: 8px;
        }



        .ratings-section {
            margin: 20px 0;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 500;
            border-top: 1px solid #dbdbdb;
            padding-top: 30px;
        }

        .ratings-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .rating-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }

        .rating-card img {
            width: 130px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .rating-title {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .review-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 35px;
        }

        .review-meta {
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: 400;
            text-align: right;
        }

        .review-meta span {
            font-weight: 700;
        }

        .review-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .review-comment {
            text-align: center;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            font-weight: 400;
        }

        .heading-comment {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        footer {
            background: #1a237e;
            color: white;
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            font-weight: 400;
            padding: 14px 0px;
        }
    </style>
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
                <strong>Dhaka</strong>, in <strong>Bangladesh.</strong>
            </p>

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
</body>

</html>