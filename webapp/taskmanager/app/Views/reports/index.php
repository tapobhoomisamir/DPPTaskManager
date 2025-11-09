<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
    // Example: Get user ID from session (adjust as needed)
    //$userId = session()->get('user_id');

    // Fetch pending tasks count for this user (from controller)
    $pendingTasks = isset($pendingTasks) ? $pendingTasks : 0;
    $currentUserId = $sessionUser["userId"]; // Replace with actual user ID from session
    $currentRole = $sessionUser["role"]; // Replace with actual user role from session
    $pageId = 'reports';
    ?>
    <!-- Header Navigation -->
<?= view('partials/header_navigation.php',['sessionUser' => $sessionUser,'pageId' =>$pageId]) ?>
<input type="hidden" id="currentUserId" name="currentUser_id" value="<?= $currentUserId ?>">
<input type="hidden" id="currentUserRole" name="currentUser_role" value="<?= $currentRole ?>">
<input type="hidden" id="pageId" name="page_id" value="<?= $pageId ?>">

<div class="container py-4">
    <h3 class="mb-4">Department vs Agenda Distribution</h3>
    <canvas id="deptAgendaBarChart" width="800" height="200"></canvas>
</div>
<div class="container py-4">
    <h3 class="mb-4">Agenda vs Status Distribution</h3>
    <canvas id="agendaStatusBarChart" width="800" height="200"></canvas>
</div>

<div class="container py-4">
    <h3 class="mb-4">Worklist Trend (Created, Completed, Due)</h3>
    <canvas id="worklistLineChart" width="800" height="300"></canvas>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("<?= base_url('api/reports/department-agenda-distribution') ?>")
        .then(res => res.json())
        .then(data => {
            // data: { departments: [...], agendas: [...], counts: { [department][agenda]: count } }
            const ctx = document.getElementById('deptAgendaBarChart').getContext('2d');
            const departments = data.departments;
            const agendas = data.agendas;
            const counts = data.counts;

            // Build datasets for each agenda
            const colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
            ];
            const datasets = agendas.map((agenda, idx) => ({
                label: agenda,
                backgroundColor: colors[idx % colors.length],
                data: departments.map(dept => (counts[dept] && counts[dept][agenda]) ? counts[dept][agenda] : 0)
            }));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: departments,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
        });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("<?= base_url('api/reports/agenda-status-distribution') ?>")
        .then(res => res.json())
        .then(data => {
            // data: { agendas: [...], statuses: [...], counts: { [agenda][status]: count } }
            const ctx = document.getElementById('agendaStatusBarChart').getContext('2d');
            const agendas = data.agendas;
            const statuses = data.statuses;
            const counts = data.counts;

            // Build datasets for each status
            const colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
            ];
            const datasets = statuses.map((status, idx) => ({
                label: status,
                backgroundColor: colors[idx % colors.length],
                data: agendas.map(agenda => (counts[agenda] && counts[agenda][status]) ? counts[agenda][status] : 0)
            }));

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: agendas,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true, beginAtZero: true }
                    }
                }
            });
        });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch("<?= base_url('api/reports/worklist-trend') ?>")
        .then(res => res.json())
        .then(data => {
            // data: { dates: [...], created: [...], completed: [...], due: [...] }
            const ctx = document.getElementById('worklistLineChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [
                        {
                            label: 'Created',
                            data: data.created,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0,123,255,0.1)',
                            fill: false,
                            tension: 0.2
                        },
                        {
                            label: 'Completed',
                            data: data.completed,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40,167,69,0.1)',
                            fill: false,
                            tension: 0.2
                        },
                        {
                            label: 'Due',
                            data: data.due,
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255,193,7,0.1)',
                            fill: false,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Date' } },
                        y: { beginAtZero: true, title: { display: true, text: 'Task Count' } }
                    }
                }
            });
        });
});
</script>
<!-- Bootstrap JS CDN (optional, for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>