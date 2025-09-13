<?php

namespace App\Controllers;

use App\Models\TaskModel; 
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Task extends BaseController
{
    public function index() {
        $taskModel = new TaskModel();
        $data['tasks'] = $taskModel->findAll();
        return view('tasks/index', $data);
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
