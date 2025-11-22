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
    <form id="reportFilterForm" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label for="filterType" class="form-label">Filter By</label>
            <select class="form-select" id="filterType" name="filterType">
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
            </select>
        </div>
        <div class="col-md-3" id="filterValueContainer">
            <label for="filterValue" class="form-label">Select Value</label>
            <select class="form-select" id="filterValue" name="filterValue"></select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Apply</button>
        </div>
    </form>
</div>
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
    function getMondayOfWeek(year, week) {
        // ISO week: Monday is the first day of the week
        const simple = new Date(year, 0, 1 + (week - 1) * 7);
        let dayOfWeek = simple.getDay();
        // 0 (Sunday) -> 7 for ISO
        if (dayOfWeek === 0) dayOfWeek = 7;
        // Adjust to previous Monday
        simple.setDate(simple.getDate() - dayOfWeek + 1);
        return simple;
    }

function populateFilterValue(type) {
    const filterValue = document.getElementById('filterValue');
    filterValue.innerHTML = '';
    const now = new Date();
    const currentYear = now.getFullYear();

    if (type === 'week') {
        // Show all weeks of current year with start/end dates
        for (let w = 1; w <= 52; w++) {
            const monday = getMondayOfWeek(currentYear, w);
            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            const startStr = monday.toISOString().slice(0, 10);
            const endStr = sunday.toISOString().slice(0, 10);
            const value = `${currentYear}-W${String(w).padStart(2, '0')}`;
            filterValue.innerHTML += `<option value="${value}">Week ${w}: ${startStr} to ${endStr}</option>`;
        }
        // Select current week
        const weekNum = Math.ceil((((now - new Date(currentYear,0,1)) / 86400000) + new Date(currentYear,0,1).getDay()+1)/7);
        filterValue.value = `${currentYear}-W${String(weekNum).padStart(2, '0')}`;
    } else if (type === 'month') {
        // Show months of current year
        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        months.forEach((month, idx) => {
            const value = `${currentYear}-${String(idx+1).padStart(2, '0')}`;
            const selected = (now.getMonth() === idx) ? 'selected' : '';
            filterValue.innerHTML += `<option value="${value}" ${selected}>${month} ${currentYear}</option>`;
        });
    } else if (type === 'year') {
        // Show years 2025-2050
        for (let y = 2025; y <= 2050; y++) {
            const selected = (y === currentYear) ? 'selected' : '';
            filterValue.innerHTML += `<option value="${y}" ${selected}>${y}</option>`;
        }
    }
}

// Initial population and chart load for current year
document.addEventListener('DOMContentLoaded', function() {
    // Set filter type to 'year'
    document.getElementById('filterType').value = 'year';
    populateFilterValue('year');
    // Set filter value to current year
    const currentYear = new Date().getFullYear();
    document.getElementById('filterValue').value = currentYear;
    // Calculate range and load charts
    const { start, end } = getRangeDates('year', currentYear);
    loadAllCharts(start, end);
});

// Change filter input options based on selection
document.getElementById('filterType').addEventListener('change', function() {
    populateFilterValue(this.value);
});

// Range calculation for API
function getRangeDates(type, value) {
    let start, end;
    if (type === 'week') {
        // value format: "YYYY-Www"
        const parts = value.split('-W');
        if (parts.length === 2) {
            const year = parseInt(parts[0]);
            const week = parseInt(parts[1]);
            // Get Monday of the week
            const simple = new Date(year, 0, (week - 1) * 7);
            const dow = simple.getDay();
            const monday = new Date(simple);
            if (dow <= 4)
                monday.setDate(simple.getDate() - simple.getDay() );
            else
                monday.setDate(simple.getDate() + 8 - simple.getDay());
            start = monday.toISOString().slice(0, 10);
            end = new Date(monday);
            end.setDate(monday.getDate() + 6);
            end = end.toISOString().slice(0, 10);
        }
    } else if (type === 'month') {
        // value format: "YYYY-MM"
        const [year, month] = value.split('-');
        start = `${year}-${month}-01`;
        // Get last day of the selected month
        const lastDay = new Date(year, parseInt(month), 0).getDate();
        end = `${year}-${month}-${String(lastDay).padStart(2, '0')}`;
    } else if (type === 'year') {
        start = `${value}-01-01`;
        end = `${value}-12-31`;
    }
    return { start, end };
}

let deptAgendaChart = null;
let agendaStatusChart = null;
let worklistLineChart = null;

function loadAllCharts(startDate = null, endDate = null) {
    const rangeParams = startDate && endDate ? `?start_date=${startDate}&end_date=${endDate}` : '';

    // Department vs Agenda
    fetch("<?= base_url('api/reports/department-agenda-distribution') ?>" + rangeParams)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('deptAgendaBarChart').getContext('2d');
            if (deptAgendaChart) deptAgendaChart.destroy(); // destroy previous chart
            const departments = data.departments;
            const agendas = data.agendas;
            const counts = data.counts;
            const colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
            ];
            const datasets = agendas.map((agenda, idx) => ({
                label: agenda,
                backgroundColor: colors[idx % colors.length],
                data: departments.map(dept => (counts[dept] && counts[dept][agenda]) ? counts[dept][agenda] : 0)
            }));
            deptAgendaChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: departments, datasets: datasets },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
                }
            });
        });

    // Agenda vs Status
    fetch("<?= base_url('api/reports/agenda-status-distribution') ?>" + rangeParams)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('agendaStatusBarChart').getContext('2d');
            if (agendaStatusChart) agendaStatusChart.destroy(); // destroy previous chart
            const agendas = data.agendas;
            const statuses = data.statuses;
            const counts = data.counts;
            const colors = [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
            ];
            const datasets = statuses.map((status, idx) => ({
                label: status,
                backgroundColor: colors[idx % colors.length],
                data: agendas.map(agenda => (counts[agenda] && counts[agenda][status]) ? counts[agenda][status] : 0)
            }));
            agendaStatusChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: agendas, datasets: datasets },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
                }
            });
        });

    // Worklist Trend
    fetch("<?= base_url('api/reports/worklist-trend') ?>" + rangeParams)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('worklistLineChart').getContext('2d');
            if (worklistLineChart) worklistLineChart.destroy(); // destroy previous chart
            worklistLineChart = new Chart(ctx, {
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
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { title: { display: true, text: 'Date' } },
                        y: { beginAtZero: true, title: { display: true, text: 'Task Count' } }
                    }
                }
            });
        });
}

// Initial chart load
loadAllCharts();

// Filter form submit
document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const type = document.getElementById('filterType').value;
    const value = document.getElementById('filterValue').value;
    const { start, end } = getRangeDates(type, value);
    loadAllCharts(start, end);
});
</script>
<!-- Bootstrap JS CDN (optional, for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>