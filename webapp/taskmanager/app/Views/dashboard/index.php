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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('reports') ?>">Reports</a>
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
</body>
</html>