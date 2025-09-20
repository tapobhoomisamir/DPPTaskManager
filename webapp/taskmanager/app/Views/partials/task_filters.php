<!-- Filter (above task list) -->
    <div class="mb-3">
        <button class="btn btn-outline-secondary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
            Filters
        </button>
        <div class="collapse" id="collapseFilter">
            <div class="card card-body">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" id="status">
                            <option value="">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Done">Done</option>
                            <option value="Awail Approval">Awail Approval</option>
                            <option value="Approved">Approved</option>
                            <option value="Closed">Closed</option>
                            <option value="Hold">Hold</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" class="form-select" id="department_id">
                            <option value="">All Departments</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tasktype_id" class="form-label">Type</label>
                        <select name="tasktype_id" class="form-select" id="tasktype_id">
                            <option value="">All Types</option>
                            <?php foreach($tasktypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" class="form-select" id="user_id">
                            <option value="">All Users</option>
                            <?php foreach($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= esc($user['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="work_week" class="form-label">Work Week</label>
                        <select name="work_week" class="form-select" id="workweek_id">
                            <option value="">All Work Weeks</option>
                            <?php foreach($workweeks as $ww): ?>
                                <option value="<?= esc($ww['id']) ?>">
                                    <?= esc($ww['workweek']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-secondary w-25">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>