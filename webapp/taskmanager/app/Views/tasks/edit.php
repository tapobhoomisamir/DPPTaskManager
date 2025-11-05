<!DOCTYPE html>
<html>
<head>
    <title>Edit Work List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
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
    <h2>Edit Work List</h2>

    <form id="editTaskForm">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <div class="mb-3">
            <label for="taskTitle" class="form-label">Work List</label>
            <input type="text" class="form-control" id="taskTitle" name="title"
                   value="<?= esc($task['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="taskDescription" class="form-label">Description</label>
            <textarea class="form-control" id="taskDescription" name="description" rows="3" required><?= esc($task['description']) ?></textarea>
        </div>

        <?php if (empty($task['private'])): ?>
        <div class="mb-3">
            <label for="taskDepartment" class="form-label">Department</label>
            <select class="form-select" id="taskDepartment" name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $task['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= esc($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="taskType" class="form-label">Agenda</label>
            <select class="form-select" id="taskType" name="tasktype_id" required>
                <option value="">Select Agenda</option>
                <?php foreach($tasktypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $task['tasktype_id'] == $type['id'] ? 'selected' : '' ?>>
                        <?= esc($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="taskUser" class="form-label">Responsible</label>
            <select class="form-select" id="taskUser" name="user_id" required>
                <option value="">Select Responsible</option>
                <?php foreach($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $task['user_id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= esc($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="work_week" class="form-label">Work Week</label>
            <select name="work_week" class="form-select" id="work_week">
                <option value="">All Work Weeks</option>
                <?php foreach($workweeks as $ww): ?>
                    <option value="<?= $ww['id'] ?>" <?= $task['workweek_id'] == $ww['id'] ? 'selected' : '' ?>>
                        <?= esc($ww['workweek']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>        
        <?php endif; ?>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <?php 
                $dueDate = !empty($task['due_date']) 
                    ? date('Y-m-d', strtotime($task['due_date'])) 
                    : '';
            ?>
            <input type="date" class="form-control" id="due_date" name="due_date"
                   value="<?= esc($dueDate) ?>" required>
        </div>

        <div class="mb-3">
            <label for="expense" class="form-label">Expense</label>
            <input type="number" class="form-control" id="expense" name="expense"
                   value="<?= esc($task['expense']) ?>">
        </div>

        <div class="mb-3">
            <label for="time_taken" class="form-label">Time taken</label>
            <input type="number" class="form-control" id="time_taken" name="time_taken"
                   value="<?= esc($task['time_taken']) ?>">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority" required>
                <?php
                    // support either 'Priority' or 'priority' key from the task array
                    $prio = '';
                    if (isset($task['priority'])) {
                        $prio = $task['priority'];
                    } elseif (isset($task['priority'])) {
                        $prio = $task['priority'];
                    }

                    $priorityOptions = ['High', 'Medium', 'Low'];
                    foreach ($priorityOptions as $opt) {
                        $selected = ($prio === $opt) ? 'selected' : '';
                        echo "<option value=\"" . esc($opt) . "\" $selected>" . esc($opt) . "</option>";
                    }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="<?= base_url('tasks/view/'.$task['id']) ?>" class="btn btn-secondary">Cancel</a>
    </form>
    <br><br>
    <?= $this->include('partials/task_attachments.php',['sessionUser' => $sessionUser]) ?>

    <br><br>
    <?= $this->include('partials/task_comments.php',['sessionUser' => $sessionUser]) ?>
</div>

<script src="/js/taskcomment.js"></script>
<script>
    

    document.getElementById('editTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const taskId = formData.get('id');

        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        fetch(`/api/tasks/${taskId}`, {
            method: 'PUT',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                // alert("Task updated successfully");                
                window.location.href = `/tasks/view/${taskId}`;
            } else {
                alert(result.message || "Failed to update task");
            }
        })
        .catch(() => alert("Error while updating task"));
    });

    /*
    Populate the #status select using the same logic as openStatusModal in tasks.js.
    This keeps status options consistent between row modal and edit page.
    */
    (function(){
        const statusSelect = document.getElementById('status');
        if (!statusSelect) return;

        const taskType = "<?= esc($task['tasktype_name']) ?>";
        const currentStatus = "<?= esc($task['status']) ?>";
        // prefer role from hidden input if present (keeps JS driven pages consistent)
        const roleEl = document.getElementById('currentUserRole');
        const role = roleEl ? roleEl.value : "<?= esc($currentRole) ?>";

        function buildStatusOptions(taskType, currentStatus, role) {
            // Base statuses
            const base = ['Pending', 'In Progress', 'Done', 'Hold','Closed'];
            const opts = [];

            if (taskType === 'Approval' || taskType.toLowerCase().includes('approval')) {
                // Approval-type task logic:
                if (currentStatus === 'Await Approval') {
                    // show Await Approval to everyone; show Approved only to approvers
                    opts.push('Await Approval');
                    if (['Administrator','Authority','Incharge'].includes(role)) {
                        opts.push('Approved');
                    }
                } else if (currentStatus === 'Approved') {
                    opts.push('Approved');
                } else {
                    // before approval stage allow normal workflow and the option to send for approval
                    opts.push(...base);
                    // add Await Approval as possible next step
                    opts.push('Await Approval');
                }
            } else {
                // non-approval task: normal statuses
                opts.push(...base);
                // keep current status even if it's an uncommon value
                if (!opts.includes(currentStatus)) opts.push(currentStatus);
            }

            // Deduplicate while preserving order
            return [...new Set(opts)];
        }

        const options = buildStatusOptions(taskType, currentStatus, role);

        // Render options with current selected
        statusSelect.innerHTML = '';
        options.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt;
            o.textContent = opt;
            if (opt === currentStatus) o.selected = true;
            statusSelect.appendChild(o);
        });
    })();
</script>
</body>
</html>
