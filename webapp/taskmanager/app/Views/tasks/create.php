<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
</head>
<body>
    <h1>Create Task</h1>

    <form action="/tasks/store" method="post">
        <label>Title</label><br>
        <input type="text" name="title" required><br><br>

        <label>Description</label><br>
        <textarea name="description"></textarea><br><br>

        <button type="submit">Save Task</button>
    </form>
</body>
</html>
