<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12pt;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        p {
            line-height: 1.6;
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            font-size: 10pt;
            color: #777;
            margin-top: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .rating {
            font-weight: bold;
            color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="header">Reviews for Profile ID: <?php echo $profile_id; ?></div>
    <p>Generated on: <?php echo date('Y-m-d'); ?></p>

    <!-- Review content -->
    <?php echo $review_content; ?>

    <div class="footer">Thank you for reviewing with us!</div>

</body>

</html>