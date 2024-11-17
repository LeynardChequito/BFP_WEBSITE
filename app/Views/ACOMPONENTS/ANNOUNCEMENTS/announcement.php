<h1>Announcements</h1>
<a href="/announcements/create">Create Announcement</a>

<?php if (session()->getFlashdata('success')): ?>
    <div><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (!empty($announcements)): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($announcements as $announcement): ?>
        <tr>
            <td><?= esc($announcement['announcement_id']) ?></td>
            <td><?= esc($announcement['title']) ?></td>
            <td><?= esc($announcement['content']) ?></td>
            <td>
                <?php if (!empty($announcement['image'])): ?>
                    <img src="<?= base_url('public/uploads/' . esc($announcement['image'])) ?>" alt="<?= esc($announcement['title']) ?>" style="width: 100px; height: auto;">
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>
            <td><?= esc($announcement['created_at']) ?></td>
            <td><?= esc($announcement['updated_at']) ?></td>
            <td>
                <a href="/announcements/edit/<?= esc($announcement['announcement_id']) ?>">Edit</a>
                <a href="/announcements/delete/<?= esc($announcement['announcement_id']) ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No announcements available.</p>
<?php endif; ?>
