<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
</head>
<body>
<h2>All Tasks</h2>
<a href="<?= base_url('tasks/create') ?>">+ New Task</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Title</th>
        <th>Project</th>
        <th>User</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach($tasks as $task): ?>
    <tr>
        <td><?= $task['title'] ?></td>
        <td><?= $task['project_id'] ?></td>
        <td><?= $task['user_id'] ?></td>
        <td><?= $task['status'] ?></td>
        <td>
            <a href="<?= base_url('tasks/updateStatus/'.$task['id']) ?>">
                Mark as <?= $task['status'] === 'Pending' ? 'Completed' : 'Pending' ?>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
