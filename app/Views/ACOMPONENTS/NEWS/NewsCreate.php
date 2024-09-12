<?= view('ACOMPONENTS/NEWS/adminnewsheader'); ?>


    <div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalLabel">Add News</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('news-store') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="newsTitle">Title</label>
                        <input type="text" class="form-control" name="title" id="newsTitle" placeholder="Enter news title" required>
                    </div>
                    <div class="form-group">
                        <label for="newsContent">Content</label>
                        <textarea class="form-control" name="content" id="newsContent" rows="3"
                                  placeholder="Enter news content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="newsImage">Image</label>
                        <input type="file" class="form-control" name="image[]" id="newsImage" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add News</button>
                </form>
            </div>
        </div>
    </div>
</div>


