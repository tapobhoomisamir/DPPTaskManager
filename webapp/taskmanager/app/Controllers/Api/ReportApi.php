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
        
        $workweekIds = $this->getWorkWeeksInRange($db);

        $builder = $db->table('tasks t')
            ->select('d.name as department, tt.name as agenda, COUNT(*) as count')
            ->join('departments d', 'd.id = t.department_id', 'left')
            ->join('tasktypes tt', 'tt.id = t.tasktype_id', 'left')
            ->groupBy('d.name, tt.name');

        $this->setBuilderDateRange($builder, $workweekIds);
        $builder->where('t.is_deleted', 0); // Exclude deleted tasks
        

        $query = $builder->get();
        
        $departments = [];
        $agendas = [];
        $counts = [];

        log_message('debug', "ReportApi::departmentAgendaDistribution processing results: " . json_encode($query->getResultArray()));
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

        $workweekIds = $this->getWorkWeeksInRange($db);

        $builder = $db->table('tasks t')
            ->select('tt.name as agenda, t.status, COUNT(*) as count')
            ->join('tasktypes tt', 'tt.id = t.tasktype_id', 'left')
            ->groupBy('tt.name, t.status');

        $this->setBuilderDateRange($builder, $workweekIds);
        $builder->where('t.is_deleted', 0); // Exclude deleted tasks

        $query = $builder->get();

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

        $workweekIds = $this->getWorkWeeksInRange($db);

        $builder = $db->table('tasks t')
            ->select("DATE(created_at) as dt, COUNT(*) as cnt")
            ->where('created_at >=', $dates[0] . ' 00:00:00')
            ->groupBy('dt');

        $this->setBuilderDateRange($builder, $workweekIds);
        $builder->where('t.is_deleted', 0); // Exclude deleted tasks

        $query = $builder->get();

        // Created
        $created = [];
        $rows = $query->getResultArray();
        $createdMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $created[] = isset($createdMap[$d]) ? (int)$createdMap[$d] : 0;

        $builder = $db->table('tasks t')
            ->select("DATE(completed_date) as dt, COUNT(*) as cnt")
            ->whereIn('status', ['Done', 'Completed'])
            ->where('completed_date >=', $dates[0] . ' 00:00:00')
            ->groupBy('dt');

        $this->setBuilderDateRange($builder, $workweekIds);
        $builder->where('t.is_deleted', 0); // Exclude deleted tasks

        $query = $builder->get();
        // Completed (status = Done or Completed)
        $completed = [];
        $rows = $query->getResultArray();
        $completedMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $completed[] = isset($completedMap[$d]) ? (int)$completedMap[$d] : 0;


        $builder = $db->table('tasks t')
            ->select("DATE(due_date) as dt, COUNT(*) as cnt")
            ->where('due_date >=', $dates[0])
            ->groupBy('dt');

        $this->setBuilderDateRange($builder, $workweekIds);
        $builder->where('t.is_deleted', 0); // Exclude deleted tasks

        $query = $builder->get();
        // Due (by due_date)
        $due = [];
        $rows =$query->getResultArray();
        $dueMap = array_column($rows, 'cnt', 'dt');
        foreach ($dates as $d) $due[] = isset($dueMap[$d]) ? (int)$dueMap[$d] : 0;

        return $this->response->setJSON([
            'dates' => $dates,
            'created' => $created,
            'completed' => $completed,
            'due' => $due
        ]);
    }

    private function getWorkWeeksInRange($db)
    {
        $start = $this->request->getGet('start_date');
        $end = $this->request->getGet('end_date');

        $wwRows = $db->table('workweeks')
            ->select('id')
            ->groupStart()
                ->where('start_date >=', $start)
                ->where('end_date <=', $end)
            ->groupEnd()
            ->orGroupStart()
                ->where('start_date <=', $end)
                ->where('end_date >=', $start)
            ->groupEnd()
            ->get()->getResultArray();
        return array_column($wwRows, 'id');
    }

    private function setBuilderDateRange($builder, $workweekIds)
    {
        // Filter by workweek IDs if range is set
        if (!empty($workweekIds)) {
            $builder->whereIn('t.workweek_id', $workweekIds);
        }
        else
        {
             $builder->whereIn('t.workweek_id', [0]);
        }
    }
}