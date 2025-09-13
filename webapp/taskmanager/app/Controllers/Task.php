<?php

namespace App\Controllers;

use App\Models\TaskModel; 
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Task extends BaseController
{
    public function index() {
        $taskModel = new TaskModel();
        
        // Fetch filter options
        $db = \Config\Database::connect();
        $departments = $db->table('departments')->get()->getResultArray();
        $tasktypes = $db->table('tasktypes')->get()->getResultArray();

        // Apply filters from GET
        $filters = $this->request->getGet();
        $builder = $taskModel->getTasksWithFiltered($filters);
        $tasks = $builder->get()->getResultArray();

        return view('tasks/index', [
            'tasks' => $tasks,
            'departments' => $departments,
            'tasktypes' => $tasktypes
        ]);
    }

    // ✅ New method
    public function create()
    {
        return view('tasks/create'); // load form page
    }

    // ✅ Optional: store data from form
    public function store()
    {
        $taskModel = new TaskModel();

        $taskModel->save([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status'      => 'Pending'
        ]);

        return redirect()->to('/tasks');
    }
}
