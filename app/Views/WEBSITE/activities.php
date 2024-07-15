<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <style>
  body {
    background-color: #f8f9fa;
  }

  .navbar {
    background-color: #ff6347; 
  }

  .navbar-brand {
    font-size: 1.5rem;
    color: #fff; 
  }

  .navbar-nav .nav-item {
    margin-right: 10px;
  }

  .navbar-nav .nav-link {
    color: #fff;
  }

  .navbar-nav .nav-link:hover {
    color: #17a2b8;
  }

  .nav-flex-icons .nav-item {
    margin-right: 0;
  }

  .nav-flex-icons .nav-link {
    color: #fff;
    font-size: 1.5rem;
  }

  .nav-flex-icons .nav-link:hover {
    color: #17a2b8;
  }
  body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
}

header {
    background-color: #003366; /* BSP Blue */
    color: #fff;
    text-align: center;
    padding: 1em;
}

main .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 1em;
}

.album {
    width: 300px;
    margin: 1em;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
}

.album:hover {
    transform: scale(1.05);
}

.album img {
    width: 100%;
    height: auto;
    border-bottom: 1px solid #ddd;
}

.album-info {
    padding: 1em;
    text-align: center;
}

.album-info h3 {
    color: #003366; /* BSP Blue */
    font-size: 1.2rem;
    margin-top: 0.5em;
}

</style>



<body>
<?= view('WEBSITE/site'); ?>
<h2 class="article-preview-heading mb-4">Activities</h2>
        <a href="javascript:history.go(-1);" class="btn btn-danger mt-3">Back</a>

    <main class="container mt-4">
        <div class="row justify-content-center">
        <div class="album">
        <img src="website/rank.jpg">
        <div class="album-info">
            <h3><strong>Rank Inspection</strong></h3>
            <a href="<?= site_url('/album') ?>" class="nav-link">Show More</a>
        </div>
    </div>

    <div class="album">
        <img src="website/intern.jpg">
        <div class="album-info">
            <h3><strong>Fire Intern</strong></h3>
            <a href="<?= site_url('/intern') ?>" class="nav-link">Show More</a>
        </div>
    </div>
    <div class="album">
        <img src="website/personnel.jpg">
        <div class="album-info">
            <h3><strong>Personnel &  Firetruck Visibility/Standby/ <br> Assistance</strong></h3>
            <a href="<?= site_url('/pfv') ?>" class="nav-link">Show More</a>
        </div>
    </div>

    <div class="album">
        <img src="website/fdas.jpg">
        <div class="album-info">
            <h3><strong>FDAS/AFSS/Manual & Automatic <br> Fire Alarm System Testing</strong></h3>
            <a href="<?= site_url('/fdas') ?>" class="nav-link">Show More</a>
        </div>
    </div>

    <div class="album">
        <img src="website/11.jpg">
        <div class="album-info">
            <h3><strong>Inspection</strong></h3>
            <a href="<?= site_url('/inspection') ?>" class="nav-link">Show More</a>
        </div>
    </div>

    
    </main>

    <?= view('hf/footer'); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Function to update Philippine time
        function updatePhilippineTime() {
            const options = { timeZone: 'Asia/Manila', hour12: true, hour: 'numeric', minute: 'numeric', second: 'numeric' };
            const philippineTime = new Date().toLocaleString('en-US', options);
            document.getElementById('philippineTime').innerText = philippineTime;
        }

        // Update time initially and set interval to update every second
        updatePhilippineTime();
        setInterval(updatePhilippineTime, 1000);
    </script>
</body>
</html>


