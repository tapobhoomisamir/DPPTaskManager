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

        return view('dashboard/index', [
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'awaitApprovalTasks' => $awaitApprovalTasks,
            'holdTasks' => $holdTasks,
            'allTasks' => $allTasks
        ]);
    }
}
