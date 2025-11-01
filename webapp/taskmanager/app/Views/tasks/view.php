<!DOCTYPE html>
<html>
<head>
    <title>Work List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
$currentUserId = $sessionUser["userId"]; // Replace with actual user ID from session
    $currentRole = $sessionUser["role"]; // Replace with actual user role from session
    $pageId = 'tasks';
?>
    <!-- Header Navigation -->
<?= view('partials/header_navigation.php',['sessionUser' => $sessionUser,'pageId' =>$pageId]) ?>
<input type="hidden" id="currentUserId" name="currentUser_id" value="<?= $currentUserId ?>">
<input type="hidden" id="currentUserRole" name="currentUser_role" value="<?= $currentRole ?>">
<div class="container mt-4">
    <h2><?= esc($task['title']) ?></h2>
    <p><strong>Description:</strong> <?= esc($task['description']) ?></p>
    <p><strong>Department:</strong> <?= esc($task['department_name']) ?></p>
    <p><strong>Agenda:</strong> <?= esc($task['tasktype_name']) ?></p>
    <p><strong>Responsible:</strong> <?= esc($task['user_name']) ?></p>
    <?php if (!empty($task['completed_date'])): ?>
        <p><strong>Completed Date:</strong> <?= esc($task['completed_date']) ?></p>
    <?php endif; ?>
    
    <?php if (!empty($task['due_date'])): ?>
        <p><strong>Due Date:</strong> <?= esc($task['due_date']) ?></p>
    <?php endif; ?>

    <?php if (!empty($task['expense'])): ?>
        <p><strong>Expense:</strong> <?= esc($task['expense']) ?></p>
    <?php endif; ?>
    <?php if (!empty($task['time_taken'])): ?>
        <p><strong>Time Taken:</strong> <?= esc($task['time_taken']) ?></p>
    <?php endif; ?>
    <?php if (!empty($task['status'])): ?>
        <p><strong>Status:</strong> <?= esc($task['status']) ?></p>
    <?php endif; ?>
    <?php if (!empty($task['status'])): ?>
        <p><strong>Priority:</strong> <?= esc($task['priority']) ?></p>
    <?php endif; ?>

    <hr>
    <?php if ($sessionUser["role"] != null && ($sessionUser["role"] === 'Administrator' || $sessionUser["role"] === 'Authority' || $sessionUser["role"] === 'Incharge')) { ?>
                
    <button class="btn btn-primary" onclick="window.location.href='<?= base_url('tasks/edit/' . $task['id']) ?>'">Edit</button>
    <?php } ?>
    <br><br>
    <?= $this->include('partials/task_attachments.php',['sessionUser' => $sessionUser]) ?>

    <br><br>
    <?= $this->include('partials/task_comments.php',['sessionUser' => $sessionUser]) ?>
    
</div>
<script src="/js/taskcomment.js"></script>
</body>
</html>