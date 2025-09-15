<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('tasks') ?>">Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reports') ?>">Reports</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <h2><?= esc($task['title']) ?></h2>
    <p><strong>Description:</strong> <?= esc($task['description']) ?></p>
    <p><strong>Department:</strong> <?= esc($task['department_name']) ?></p>
    <p><strong>Type:</strong> <?= esc($task['tasktype_name']) ?></p>
    <p><strong>Assigned To:</strong> <?= esc($task['user_name']) ?></p>
    <p><strong>Status:</strong> <?= esc($task['status']) ?></p>
    <p><strong>Due Date:</strong> <?= esc($task['due_date']) ?></p>

    <hr>

    <h4>Comments</h4>
    <?php if (!empty($comments)): ?>
        <ul class="list-group mb-3">
            <?php foreach ($comments as $c): ?>
                <li class="list-group-item">
                    <strong><?= esc($c['user_id']) ?>:</strong>
                    <?= esc($c['comment']) ?>
                    <br>
                    <small class="text-muted"><?= $c['created_at'] ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <!-- Add Comment Form -->
    <form method="post" id="commentForm">
        <input type="hidden" id="commentTaskId" name="task_id" value="<?= $task['id'] ?>">
        <div class="mb-3">
            <label for="comment" class="form-label">Add Comment</label>
            <textarea class="form-control" name="comment" id="taskComment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Comment</button>
    </form>
</div>
<script>
    const BASE_URL = "<?= base_url() ?>";
    const apiUrl = `${BASE_URL}api/tasks`;
    document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const taskId = document.getElementById('commentTaskId').value;
    const comment = document.getElementById('taskComment').value;

    fetch(`${BASE_URL}api/tasks/${taskId}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ comment })
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            
           // alert('Comment added successfully');
        } else {
            alert(result.message || 'Failed to add comment.');
        }
    })
    .catch(() => alert('Failed to add comment.'));
});
</script>
</body>
</html>