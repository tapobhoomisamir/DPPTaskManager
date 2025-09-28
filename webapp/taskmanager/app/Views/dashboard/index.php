<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- ...existing code... -->
    <?php
    // Example: Get user ID from session (adjust as needed)
    //$userId = session()->get('user_id');

    // Fetch pending tasks count for this user (from controller)
    $pendingTasks = isset($pendingTasks) ? $pendingTasks : 0;
    $currentUserId = $sessionUser["userId"]; // Replace with actual user ID from session
    $currentRole = $sessionUser["role"]; // Replace with actual user role from session
    $pageId = 'dashboard';
    ?>
    <!-- Header Navigation -->
    <?= view('partials/header_navigation.php',['sessionUser' => $sessionUser,'pageId' =>$pageId]) ?>
    
    <input type="hidden" id="currentUserId" name="currentUser_id" value="<?= $currentUserId ?>">
    <input type="hidden" id="currentUserRole" name="currentUser_role" value="<?= $currentRole ?>">
    <input type="hidden" id="pageId" name="page_id" value="<?= $pageId ?>">
    <div class="container">
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-bg-primary mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('all')">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">All Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($allTasks) ? $allTasks : 0 ?></p>
                        <small>Total</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-light mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('allassigned')">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">All Assigned Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($allAssignedTasks) ? $allAssignedTasks : 0 ?></p>
                        <small>Total assigned</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-warning mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('Pending')">
                    <div class="ccard-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Pending Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($pendingTasks) ? $pendingTasks : 0 ?></p>
                        <small>For you</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-info mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('In Progress')">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">In Progress</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($inProgressTasks) ? $inProgressTasks : 0 ?></p>
                        <small>Currently working</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-secondary mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('Await Approval')">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Await Approval</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($awaitApprovalTasks) ? $awaitApprovalTasks : 0 ?></p>
                        <small>Waiting for approval</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-dark mb-2" style="min-height:120px; cursor:pointer;" 
         onclick="filterTasks('Hold')">
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
            <?php if ($currentRole === 'Administrator' || $currentRole === 'Authority' || $currentRole === 'Incharge') { ?>                    
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">+ New Task</button>
            <?php } ?>
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
       document.getElementById('userFilterDiv').style.display = 'none'; // Hide user filter by default
      fetchTasks(1); 
  });

  
</script>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>