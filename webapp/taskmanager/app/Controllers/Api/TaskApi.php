<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;

class TaskApi extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format = 'json';

    // GET /api/tasks
    public function index()
    {
        //$tasks = $this->model->findAll();
        $tasks = $this->model->getTasksWithUser();
    
        return $this->respond($tasks);
    }

    // GET /api/tasks/(:id)
    public function show($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');
        return $this->respond($task);
    }

    // POST /api/tasks
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondCreated([
            'message' => 'Task created successfully',
            'task_id' => $this->model->insertID()
        ]);
    }

    // PUT /api/tasks/(:id)
    public function update($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');

        $data = $this->request->getJSON(true);
        $this->model->update($id, $data);

        return $this->respond([
            'message' => 'Task updated successfully'
        ]);
    }

    // DELETE /api/tasks/(:id)
    public function delete($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');

        $this->model->delete($id);
        return $this->respond([
            'message' => 'Task deleted successfully'
        ]);
    }
}
