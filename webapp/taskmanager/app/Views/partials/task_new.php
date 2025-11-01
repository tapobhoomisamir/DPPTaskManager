
<div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="newTaskForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newTaskModalLabel">Create New Work</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="taskTitle" class="form-label">Work</label>
                <input type="text" class="form-control" id="taskTitle" name="title" required>
            </div>
            <div class="mb-3">
                <label for="taskDescription" class="form-label">Description</label>
                <textarea class="form-control" id="taskDescription" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="taskDepartment" class="form-label">Department</label>
                <select class="form-select" id="taskDepartment" name="department_id" required>
                    <option value="">Select Department</option>
                    <?php foreach($departments as $dept): ?>
                        <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="taskType" class="form-label">Agenda</label>
                <select class="form-select" id="taskType" name="tasktype_id" required>
                    <option value="">Select Agenda</option>
                    <?php foreach($tasktypes as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="taskUser" class="form-label">Responsible</label>
                <select class="form-select" id="taskUser" name="user_id" required>
                    <option value="">Select Reponsible</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= esc($user['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="work_week" class="form-label">Work Week</label>
                <select name="work_week" class="form-select" id="work_week">
                    <option value="">All Work Weeks</option>
                    <?php foreach($workweeks as $ww): ?>
                        <option value="<?= esc($ww['id']) ?>">
                            <?= esc($ww['workweek']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Task</button>
        </div>
      </div>
    </form>
  </div>
</div>