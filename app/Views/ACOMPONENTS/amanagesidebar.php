<div class="col-lg-2">
    <div class="sidebar">
        <h2 class="text-center mb-4">BFP Admin</h2>
        <ul class="list-unstyled components">
            <hr style="background-color: white; height: 2px; border: none;">

            <li>
                <a href="<?= site_url('/admin-home') ?>">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <a href="<?= site_url('/newscreate') ?>">
                    <i class="fas fa-newspaper mr-2"></i>Manage News
                </a>
            </li>
            <li>
                <a href="<?= site_url('carousel') ?>">
                    <i class="fas fa-images mr-2"></i>Manage Carousel
                </a>
            </li>
            <hr style="background-color: white; height: 2px; border: none;">

            
            <li>
                <a href="<?= site_url('rescuer-report/form') ?>">
                   <i class="fas fa-file-alt mr-2"></i> Add Fire Report
                </a>
            </li>
            <hr style="background-color: white; height: 2px; border: none;">

            <li>
                <a href="<?= site_url('graph') ?>">
                    <i class="fas fa-chart-pie mr-2"></i>Dashboard
                </a>
            </li>
        </ul>
        <hr style="background-color: white; height: 2px; border: none;">

        <a href="<?= site_url('/admin-registration') ?>" class="create-account-btn btn btn-danger btn-block mt-4">Create an Account</a>
        <a class="btn btn-danger btn-block mt-4" href="<?= site_url('/admin-logout') ?>">Logout</a>
    </div>
</div>