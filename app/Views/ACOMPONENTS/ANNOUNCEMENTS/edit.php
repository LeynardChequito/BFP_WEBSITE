<h1>Edit Announcement</h1>

<form action="/announcements/update/<?= esc($announcement['announcement_id']) ?>" method="post" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?= esc($announcement['title']) ?>" required>
    <br>
    <label for="content">Content:</label>
    <textarea name="content" required><?= esc($announcement['content']) ?></textarea>
    <br>
    <label for="image">Upload New Image (optional):</label>
    <input type="file" name="image" accept="image/*">
    <br>
    <?php if (!empty($announcement['image'])): ?>
        <p>Current Image:</p>
        <img src="<?= base_url('uploads/' . esc($announcement['image'])) ?>" alt="<?= esc($announcement['title']) ?>" style="width: 100px; height: auto;">
    <?php else: ?>
        <p>No current image.</p>
    <?php endif; ?>
    <br>
    <button type="submit">Update</button>
</form>
<a href="/announcements">Back</a>
