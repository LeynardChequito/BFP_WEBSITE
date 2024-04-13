<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .album {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        margin: 20px;
    }

    .album-info {
        text-align: center;
        width: 100%;
        margin-bottom: 20px;
    }

    .album img {
        width: 100%;
        max-width: 300px;
        height: auto;
        margin: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        transition: transform 0.3s ease-in-out;
        cursor: pointer;
    }

    .album img:hover {
        transform: scale(1.1);
    }

    .btn btn-danger mt-3{
        align-items: center;
    }
</style>

<body>
<?= $this->renderSection('content') ?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<div class="album">
<div class="album-info">
            <h3><strong>FDAS/AFSS/Manual & Automatic Fire Alarm System Testing</strong></h3>
            <a href="<?= site_url('/Show More') ?>" class="nav-link"></a>
           
        <img src="website/fdas.jpg" alt="Photo 1">
    <img src="website/fdas/1.jpg" alt="Photo 2">
    <img src="website/fdas/2.jpg" alt="Photo 3">
    <img src="website/fdas/3.jpg" alt="Photo 4">
    <img src="website/fdas/4.jpg" alt="Photo 2">
    <img src="website/fdas/5.jpg" alt="Photo 3">
   
    
    <a href="javascript:history.go(-1);" class="btn btn-danger mt-3">Back</a>
    <!-- Add more images as needed -->
    </div>
    
</div>

</body>
</html>
