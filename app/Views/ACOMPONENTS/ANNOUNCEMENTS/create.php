<h1>Create Announcement</h1>

<form action="/announcements/store" method="post" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" name="title" required>
    <br>
    <label for="content">Content:</label>
    <textarea name="content" required></textarea>
    <br>
    <label for="image">Upload Image:</label>
    <input type="file" name="image" accept="image/*" required>
    <br>
    <button type="submit">Create</button>
</form>
<a href="/announcements">Back</a>