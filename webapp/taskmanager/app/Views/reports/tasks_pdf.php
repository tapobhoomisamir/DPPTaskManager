<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tasks Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Tasks Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Department</th>
                <th>Type</th>
                <th>Assigned To</th>
                <th>Assigned By</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Expense</th>
                <th>Time taken</th>
                <th>Work week</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= $task['id'] ?></td>
                    <td><?= esc($task['title']) ?></td>
                    <td><?= esc($task['department_name']) ?></td>
                    <td><?= esc($task['tasktype_name']) ?></td>
                    <td><?= esc($task['user_name']) ?></td>
                    <td><?= esc($task['assign_by_name']) ?></td>
                    <td><?= esc($task['status']) ?></td>
                    <td><?= esc($task['due_date']) ?></td>
                    <td><?= esc($task['expense']) ?></td>
                    <td><?= esc($task['time_taken']) ?></td>
                    <td><?= esc($task['workweek_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
