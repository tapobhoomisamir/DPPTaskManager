<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $taskModel = new \App\Models\TaskModel();

        $statuses = ['Pending', 'In Progress', 'Await Approval','Hold'];

        $allTasks = $taskModel
            ->where('user_id', 1)
            ->wherein('status', $statuses)
            ->countAllResults();

        $pendingTasks = $taskModel
            ->where('user_id', 1)
            ->where('status', 'Pending')
            ->countAllResults();

        $inProgressTasks = $taskModel
            ->where('user_id', 1)
            ->where('status', 'In Progress')
            ->countAllResults();

        $awaitApprovalTasks = $taskModel
            ->where('user_id', 1)
            ->where('status', 'Await Approval')
            ->countAllResults();

        $holdTasks = $taskModel
            ->where('user_id', 1)
            ->where('status', 'Hold')
            ->countAllResults();

        // Fetch filter options
        $db = \Config\Database::connect();
        $departments = $db->table('departments')->get()->getResultArray();
        $tasktypes = $db->table('tasktypes')->get()->getResultArray();
        $users = $db->table('users')->get()->getResultArray();
        $today = date('Y-m-d 00:00:00'); // current date
        $minus10 = date('Y-m-d 00:00:00', strtotime('-10 days'));
        $add30 = date('Y-m-d 00:00:00', strtotime('+30 days'));

        $workweeks = $db->table('workweeks')
                ->where('start_date >',$minus10)
                ->where('end_date <',$add30)->get()->getResultArray();

        // Apply filters from GET
        $filters = $this->request->getGet();
        $builder = $taskModel->getTasksWithFiltered($filters);
        $tasks = $builder->get()->getResultArray();

        return view('dashboard/index', [
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'awaitApprovalTasks' => $awaitApprovalTasks,
            'holdTasks' => $holdTasks,
            'allTasks' => $allTasks,
            'tasks' => $tasks,
            'departments' => $departments,
            'tasktypes' => $tasktypes,
            'users'       => $users,
            'workweeks' => $workweeks,
        ]);
    }
}
