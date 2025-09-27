<h4>Comments</h4>
    <?php if (!empty($comments)): ?>
        <ul class="list-group mb-3">
            <?php foreach ($comments as $c): ?>
                <li class="list-group-item">
                    <strong><?= esc($c['user_name']) ?>:</strong>
                    <?= esc($c['comment']) ?>
                    <br>
                    <small class="text-muted"><?= $c['created_at'] ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <?php if ($sessionUser["role"] != null && ($sessionUser["role"] === 'Administrator' || $sessionUser["role"] === 'Authority' || $sessionUser["role"] === 'Incharge'  || $sessionUser["role"] === 'Executor')) { ?>
 
    <!-- Add Comment Form -->
    <form method="post" id="commentForm">
        <input type="hidden" id="commentTaskId" name="task_id" value="<?= $task['id'] ?>">
        <div class="mb-3">
            <label for="comment" class="form-label">Add Comment</label>
            <textarea class="form-control" name="comment" id="taskComment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Comment</button>
    </form>
    <?php } ?>