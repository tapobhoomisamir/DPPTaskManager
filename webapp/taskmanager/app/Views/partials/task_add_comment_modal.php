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
            <input type="hidden" id="commentTaskStatus" name="task_status">
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