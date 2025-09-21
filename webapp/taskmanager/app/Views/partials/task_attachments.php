<h4>Attachments</h4>

<!-- Upload Form -->
<form action="<?= base_url('tasks/uploadAttachment/' . $task['id']) ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <input type="file" name="attachment" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
</form>

<hr>

<!-- Show Existing Attachments -->
<?php if (!empty($attachments)): ?>
    <ul class="list-group">
        <?php foreach ($attachments as $a): ?>
            <li class="list-group-item">
                <a href="<?= base_url($a['file_path']) ?>" target="_blank"><?= esc($a['file_name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No files uploaded yet.</p>
<?php endif; ?>
