<?php

namespace App\Controllers;

use App\Models\TaskModel; 
use App\Models\CommentModel; 
use App\Models\DepartmentModel; 
use App\Models\TaskTypeModel; 
use App\Models\UserModel;
use App\Models\WorkWeekModel;
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
        $users = $db->table('users')->get()->getResultArray();
        $workweeks = $db->table('workweeks')->get()->getResultArray();

        // Apply filters from GET
        $filters = $this->request->getGet();
        $builder = $taskModel->getTasksWithFiltered($filters);
        $tasks = $builder->get()->getResultArray();

        return view('tasks/index', [
            'tasks' => $tasks,
            'departments' => $departments,
            'tasktypes' => $tasktypes,
            'users'       => $users,
            'workweeks' => $workweeks,
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

    public function view($id)
    {
        $taskModel = new TaskModel();
        $commentModel = new CommentModel();

       

        // fetch task details
        $task = $taskModel
            ->select('tasks.*, departments.name as department_name, tasktypes.name as tasktype_name, users.name as user_name')
            ->join('departments', 'departments.id = tasks.department_id', 'left')
            ->join('tasktypes', 'tasktypes.id = tasks.tasktype_id', 'left')
            ->join('users', 'users.id = tasks.user_id', 'left')
            ->find($id);

        if (!$task) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task not found");
        }

        // fetch comments
        $comments = $commentModel
            ->where('task_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('tasks/view', [
            'task' => $task,
            'comments' => $comments,
        ]);
    }

    

    // Edit form page
    public function edit($id)
    {
        $taskModel = new TaskModel();
        $commentModel = new CommentModel();

         // Fetch filter options
        $db = \Config\Database::connect();
        $departments = $db->table('departments')->get()->getResultArray();
        $tasktypes = $db->table('tasktypes')->get()->getResultArray();
        $users = $db->table('users')->get()->getResultArray();
        $workweeks = $db->table('workweeks')->get()->getResultArray();

        // fetch task details
        $task = $taskModel
            ->select('tasks.*, departments.name as department_name, tasktypes.name as tasktype_name, users.name as user_name')
            ->join('departments', 'departments.id = tasks.department_id', 'left')
            ->join('tasktypes', 'tasktypes.id = tasks.tasktype_id', 'left')
            ->join('users', 'users.id = tasks.user_id', 'left')
            ->find($id);

        if (!$task) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task not found");
        }

        // fetch comments
        $comments = $commentModel
            ->where('task_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('tasks/edit', [
            'task' => $task,
            'departments' => $departments,
            'tasktypes' => $tasktypes,
            'users'       => $users,
            'workweeks' => $workweeks,
            'comments' => $comments,
        ]);
    }
}

