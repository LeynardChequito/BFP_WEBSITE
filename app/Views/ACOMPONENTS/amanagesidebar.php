
<div class="col-md-2">
    <div class="sidebar">
        <h2 class="text-center mb-4">BFP Admin</h2>
        <ul class="list-unstyled components">
            <li>
                <a href="<?= site_url('/admin-manage') ?>">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <a href="<?= site_url('/newscreate') ?>">
                    <i class="fas fa-newspaper mr-2"></i>Manage News
                </a>
            </li>
            <li>
                <a href="<?= site_url('carouselImages') ?>">
                    <i class="fas fa-images mr-2"></i>Carousel
                </a>
            </li>
        </ul>
        <hr>
        <a class="btn btn-danger btn-block mt-4" href="<?= site_url('/admin-logout') ?>">Logout</a>
    </div>
</div>
