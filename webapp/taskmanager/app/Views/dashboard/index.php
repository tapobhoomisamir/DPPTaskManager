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
                        <a class="nav-link active" href="<?= base_url('') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tasks') ?>">Tasks</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- ...existing code... -->
    <?php
    // Example: Get user ID from session (adjust as needed)
    //$userId = session()->get('user_id');

    // Fetch pending tasks count for this user (from controller)
    $pendingTasks = isset($pendingTasks) ? $pendingTasks : 0;
    ?>
    <div class="container">
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-bg-primary mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">All Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($allTasks) ? $allTasks : 0 ?></p>
                        <small>Total assigned</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-warning mb-2" style="min-height:120px;">
                    <div class="ccard-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Pending Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($pendingTasks) ? $pendingTasks : 0 ?></p>
                        <small>For you</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-info mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">In Progress</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($inProgressTasks) ? $inProgressTasks : 0 ?></p>
                        <small>Currently working</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-secondary mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Await Approval</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($awaitApprovalTasks) ? $awaitApprovalTasks : 0 ?></p>
                        <small>Waiting for approval</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-dark mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Hold</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($holdTasks) ? $holdTasks : 0 ?></p>
                        <small>On hold</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="statusForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="statusTaskId" name="task_id">
                <label for="newStatus">Change Status</label>
                <select id="newStatus" class="form-control"></select>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Add Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="commentForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Add Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="commentTaskId" name="task_id">
                <div class="mb-3">
                    <label for="taskComment" class="form-label">Comment</label>
                    <textarea class="form-control" id="taskComment" name="comment" rows="3" required></textarea>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Comment</button>
                </div>
            </div>
            </form>
        </div>
    </div>

 <script src="/js/tasks.js"></script>
<script>
  // Initialize tasks when page loads
  document.addEventListener('DOMContentLoaded', function() {
      fetchTasks(1); 
  });

  
</script>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>