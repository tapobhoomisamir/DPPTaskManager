<!-- Task List -->
    <div class="row">
        <div class="col-12" id="mainContentCol">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-hover align-middle" style="min-width: 800px;">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" id="downloadReport">
                            <i class="bi bi-file-earmark-excel"></i> Download Works
                        </button>
                    </div>
                    <thead class="table-light">
                        <tr>
                            <th>Agenda</th>
                            <th>Work</th>
                            <th>Latest Comment</th>
                            <th>Department</th>
                            <th>Responsible</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tasksBody">
                        <!-- Tasks will be rendered here -->
                    </tbody>
                </table>
            </div>
            <nav>
                <ul class="pagination" id="pagination">
                    <!-- Pagination will be rendered here -->
                </ul>
            </nav>
        </div>
    </div>