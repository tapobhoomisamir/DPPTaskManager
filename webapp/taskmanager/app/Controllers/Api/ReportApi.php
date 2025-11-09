<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;
use App\Models\CommentModel; 

class ReportApi extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format = 'json';


    public function departmentAgendaDistribution()
    {
        $db = \Config\Database::connect();
        $query = $db->table('tasks t')
            ->select('d.name as department, tt.name as agenda, COUNT(*) as count')
            ->join('departments d', 'd.id = t.department_id', 'left')
            ->join('tasktypes tt', 'tt.id = t.tasktype_id', 'left')
            ->groupBy('d.name, tt.name')
            ->get();

        $departments = [];
        $agendas = [];
        $counts = [];

        foreach ($query->getResultArray() as $row) {
            $dept = $row['department'] ?? 'Others';
            $agenda = $row['agenda'] ?? 'Others';
            $departments[$dept] = true;
            $agendas[$agenda] = true;
            $counts[$dept][$agenda] = (int)$row['count'];
        }

        $departments = array_keys($departments);
        $agendas = array_keys($agendas);

        return $this->response->setJSON([
            'departments' => $departments,
            'agendas' => $agendas,
            'counts' => $counts
        ]);
    }

    public function agendaStatusDistribution()
    {
        $db = \Config\Database::connect();
        $query = $db->table('tasks t')
            ->select('tt.name as agenda, t.status, COUNT(*) as count')
            ->join('tasktypes tt', 'tt.id = t.tasktype_id', 'left')
            ->groupBy('tt.name, t.status')
            ->get();

        $agendas = [];
        $statuses = [];
        $counts = [];

        foreach ($query->getResultArray() as $row) {
            $agenda = $row['agenda'] ?? 'Others';
            $status = $row['status'] ?? 'Others';
            $agendas[$agenda] = true;
            $statuses[$status] = true;
            $counts[$agenda][$status] = (int)$row['count'];
        }

        $agendas = array_keys($agendas);
        $statuses = array_keys($statuses);

        return $this->response->setJSON([
            'agendas' => $agendas,
            'statuses' => $statuses,
            'counts' => $counts
        ]);
    }

    public function worklistTrend()
    {
        $db = \Config\Database::connect();

        // Get last 14 days (or adjust as needed)
        $days = 30;
        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }

        // Created
        $created = [];
        $rows = $db->table('tasks')
            ->select("DATE(created_at) as dt, COUNT(*) as cnt")
            ->where('created_at >=', $dates[0] . ' 00:00:00')
            ->groupBy('dt')
            ->get()->getResultArray();
        $createdMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $created[] = isset($createdMap[$d]) ? (int)$createdMap[$d] : 0;

        // Completed (status = Done or Completed)
        $completed = [];
        $rows = $db->table('tasks')
            ->select("DATE(completed_date) as dt, COUNT(*) as cnt")
            ->whereIn('status', ['Done', 'Completed'])
            ->where('completed_date >=', $dates[0] . ' 00:00:00')
            ->groupBy('dt')
            ->get()->getResultArray();
        $completedMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $completed[] = isset($completedMap[$d]) ? (int)$completedMap[$d] : 0;

        // Due (by due_date)
        $due = [];
        $rows = $db->table('tasks')
            ->select("DATE(due_date) as dt, COUNT(*) as cnt")
            ->where('due_date >=', $dates[0])
            ->groupBy('dt')
            ->get()->getResultArray();
        $dueMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $due[] = isset($dueMap[$d]) ? (int)$dueMap[$d] : 0;

        return $this->response->setJSON([
            'dates' => $dates,
            'created' => $created,
            'completed' => $completed,
            'due' => $due
        ]);
    }
}