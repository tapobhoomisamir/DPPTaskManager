<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
    // Example: Get user ID from session (adjust as needed)
    //$userId = session()->get('user_id');

    // Fetch pending tasks count for this user (from controller)
    $pendingTasks = isset($pendingTasks) ? $pendingTasks : 0;
    $currentUserId = $sessionUser["userId"]; // Replace with actual user ID from session
    $currentRole = $sessionUser["role"]; // Replace with actual user role from session
    $pageId = 'tasks';
    ?>
    <!-- Header Navigation -->
<?= view('partials/header_navigation.php',['sessionUser' => $sessionUser,'pageId' =>$pageId]) ?>
<input type="hidden" id="currentUserId" name="currentUser_id" value="<?= $currentUserId ?>">
<input type="hidden" id="currentUserRole" name="currentUser_role" value="<?= $currentRole ?>">
<input type="hidden" id="pageId" name="page_id" value="<?= $pageId ?>">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">All Tasks</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">+ New Task</button>
    </div>
    <?= $this->include('partials/task_filters.php') ?>
    <?= $this->include('partials/task_list.php') ?>
</div>
<!-- New Task Modal -->
<?= $this->include('partials/task_new.php') ?>
<!-- Change Status Modal -->
<?= $this->include('partials/task_add_status_modal.php') ?>

<!-- Add Comment Modal -->
<?= $this->include('partials/task_add_comment_modal.php') ?>

<script src="/js/tasks.js"></script>
<script>
  // Initialize tasks when page loads
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('userFilterDiv').style.display = 'block'; // Show user filter by default
      fetchTasks(1); 
  });

  
</script>

<!-- Bootstrap JS CDN (optional, for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>