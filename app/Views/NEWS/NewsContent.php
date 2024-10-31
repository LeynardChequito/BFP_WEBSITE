<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            margin-top: 20px;
        }

        .card {
            transition: transform 0.2s;
            width: 100%; /* Make the card take the full width */
            border: none; /* Remove border */
            border-radius: 15px; /* Add border-radius for a modern look */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Add box-shadow for depth */
        }

        .card:hover {
            transform: scale(1.02); /* Slightly increase scale on hover */
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 2.5rem; /* Increased font size for title */
            margin-bottom: 20px; /* Increased margin for better spacing */
            color: #333; /* Darker color for better contrast */
            font-weight: bold; /* Added font-weight for emphasis */
        }

        .card-text {
            font-size: 1.2rem; /* Increased font size for text */
            line-height: 1.8; /* Increased line height for better readability */
            color: #555; /* Slightly darker color for better contrast */
        }

        .news-image {
            max-width: 100%; /* Ensure the image fits within its container */
            height: auto; /* Maintain the image aspect ratio */
            border-radius: 15px; /* Add border-radius for a modern look */
            margin-bottom: 20px; /* Added margin for spacing */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box-shadow for depth */
        }

        .btn-back {
            margin-top: 20px; /* Increased margin for better spacing */
            font-size: 1.2rem; /* Increased font size for button */
            font-weight: bold; /* Added font-weight for emphasis */
            color: #fff; /* White text color */
            background-color: #dc3545; /* Red color for contrast */
            border: none; /* Remove border */
            border-radius: 10px; /* Add border-radius for a modern look */
            padding: 12px 24px; /* Increased padding for better clickability */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .btn-back:hover {
            background-color: #c82333; /* Darker red color on hover */
        }

        /* Added custom style for dynamic column layout */
        .dynamic-columns {
            column-count: 1; /* Adjust the column count as needed */
            column-gap: 20px;
        }

    .card-footer {
        background-color: #f8f9fa; 
        padding: 7.5px; 
        border-top: 1px solid #dee2e6; 
        border-radius: 0 0 15px 15px; 
}

.card-footer h12 {
    color: #595959; 
    font-size: 0.85rem; 
    margin-bottom: 5px; 
    font-weight: bold; 
}

.card-footer a {
    color: #007bff; 
    text-decoration: none; 
    font-size: .80rem; 
    transition: color 0.3s ease; 
    margin-left: -10px;
}

.card-footer a:hover {
    color: #0056b3; 
}

    </style>
</head>

<body>

<?= view('WEBSITE/site'); ?>
    <div class="container mt-4">
        <a href="javascript:history.go(-1);" class="btn btn-danger btn-back">Back</a>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body dynamic-columns">
                        <h2 class="card-title"><?= esc($news['title']) ?></h2>
                        <?php
                            $photoPath = base_url('bfpcalapancity/public/newsphoto/' . esc($news['image']));
                            $newsBody = esc($news['content']);
                        ?>
                        <img src="<?= $photoPath ?>" alt="News Photo" class="news-image">
                        
                        <p class="card-text"><?= $newsBody ?></p>
                    </div>
                    <div class="card-footer">
                        <h12>VALIANT FIREFIGHTERS OF CALAPAN CITY<br> In case of fire and emergency Call CCFS Hotline: 288-7777 / 09156031561 / 09814782880</h12>
                        <a href="<?= site_url('contact-us') ?>" class="nav-link">See more...</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= view('hf/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
