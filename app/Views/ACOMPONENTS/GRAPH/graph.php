<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<?= view('ACOMPONENTS/NEWS/adminnewsheader'); ?>

<body>
    <?= view('ACOMPONENTS/adminheader'); ?>

    <div class="container-fluid">

        <div class="row">
            <?= view('ACOMPONENTS/amanagesidebar'); ?>

            <div class="col-md-10">
                <div class="content">
                    <div class="container mt-5">
                        <div class="row">
                            <!-- Column for Pie Chart -->
                            <div class="col-md-4">
                                <canvas id="myPieChart" width="400" height="400"></canvas>
                            </div>
                            <!-- Column for Bar Graph -->
                            <div class="col-md-4">
                                <canvas id="myBarChart" width="400" height="400"></canvas>
                            </div>
                            <!-- Column for Line Graph -->
                            <div class="col-md-4">
                                <canvas id="myLineChart" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= view('COMPONENTS/footer'); ?>

    </div>

    <?= view('ACOMPONENTS/CAROUSEL/crsladdImages'); ?>

    <div class="modal fade" id="editCarouselModal" tabindex="-1" role="dialog" aria-labelledby="editCarouselModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarouselModalLabel">Edit Carousel Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCarouselForm" action="<?= base_url('carousel/update') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="carousel_id" id="editCarouselId" value="">

                        <div class="form-group">
                            <label for="editCarouselImage">Image</label>
                            <input type="file" class="form-control" id="editCarouselImage" name="image_path">
                            <img src="" alt="Carousel Image" class="img-thumbnail" id="editCarouselImagePreview" style="max-width: 100%; height: auto;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditForm()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setEditModal(carousel_id, image_path) {
            $('#editCarouselId').val(carousel_id);
            $('#editCarouselImagePreview').attr('src', '<?= base_url('public/carousel_images/') ?>' + image_path);
            $('#editCarouselModal').modal('show');
        }

        $(document).on('click', '.edit-carousel-btn', function() {
            var carousel_id = $(this).data('carousel_id');
            var image_path = $(this).data('image_path');

            setEditModal(carousel_id, image_path);
        });

        function submitEditForm() {
            $('#editCarouselForm').submit();
        }

        function showDeleteConfirmation(carousel_id) {
            var confirmation = confirm("Are you sure you want to delete this carousel image?");
            if (confirmation) {
                window.location.href = '<?= base_url('carousel/delete/') ?>' + carousel_id;
            }
        }

        $(document).on('click', '.delete-carousel-btn', function() {
            var carousel_id = $(this).data('carousel_id');
            showDeleteConfirmation(carousel_id);
        });

        <?php if (session()->has('success')) : ?>
            window.location.href = '<?= base_url('crsladdimages') ?>';
        <?php endif; ?>
    </script>
</body>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    // Sample data for the pie chart
    var pieData = {
        labels: ['Label 1', 'Label 2', 'Label 3'],
        datasets: [{
            data: [30, 50, 20],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Sample data for the bar graph
    var barData = {
        labels: ['Label A', 'Label B', 'Label C'],
        datasets: [{
            label: 'Bar Dataset',
            data: [10, 20, 30],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Sample data for the line graph
    var lineData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Line Dataset',
            data: [10, 20, 30, 40, 50, 60, 70],
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    };

    // Get the context of the canvas elements we want to select
    var ctxPie = document.getElementById('myPieChart').getContext('2d');
    var ctxBar = document.getElementById('myBarChart').getContext('2d');
    var ctxLine = document.getElementById('myLineChart').getContext('2d');

    // Create the pie chart
    var myPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Create the bar graph
    var myBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: barData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Create the line graph
    var myLineChart = new Chart(ctxLine, {
        type: 'line',
        data: lineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</html>