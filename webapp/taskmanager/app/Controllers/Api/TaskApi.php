<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;
use App\Models\CommentModel; 

class TaskApi extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format = 'json';

    // GET /api/tasks
    public function index()
    {
        $filters = $this->request->getGet(); // Get query parameters

        $page     = isset($filters['page']) ? (int)$filters['page'] : 1;
        $perPage  = isset($filters['per_page']) ? (int)$filters['per_page'] : 10;


        $tasks = $this->model->getTasksWithFiltered($filters)
            ->paginate($perPage, 'default', $page);

    
        $pager = $this->model->pager;

        return $this->respond([
            'tasks' => $tasks,
            'pager' => [
                'currentPage'   => $pager->getCurrentPage('default'),
                'totalPages'    => $pager->getPageCount('default'),
                'totalTasks'    => $pager->getTotal('default'),
                'perPage'       => $pager->getPerPage('default'),
            ]
        ]);
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
            'task_id' => $this->model->insertID(),
            'success' => true
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
            'message' => 'Task updated successfully',
            'success' => true
        ]);
    }

    // DELETE /api/tasks/(:id)
    public function delete($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');

        $this->model->delete($id);
        return $this->respond([
            'message' => 'Task deleted successfully',
            'success' => true
        ]);
    }

    // PUT /api/tasks/{id}/status
    public function updateStatus($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');

        $data = $this->request->getJSON(true);
        if (!isset($data['status'])) {
            return $this->failValidationErrors('Status is required');
        }

        // Prepare update data
        $updateData = ['status' => $data['status']];

        // If status is "done", set completed_date = NOW
        if (strtolower($data['status']) === 'closed') {
            $updateData['completed_date'] = date('Y-m-d H:i:s');
        }

        $this->model->update($id, $updateData);

        return $this->respond([
            'success' => true,
            'message' => 'Task status updated successfully',
        ]);
    }

    // POST /api/tasks/{id}/comments
    public function addComment($id = null)
    {
        $task = $this->model->find($id);
        if (!$task) return $this->failNotFound('Task not found');

        $data = $this->request->getJSON(true);
        if (!isset($data['comment']) || trim($data['comment']) === '') {
            return $this->failValidationErrors('Comment is required');
        }

        $commentModel = new CommentModel();
        $commentData = [
            'task_id'  => $id,
            'comment'  => $data['comment'],
            'user_id'  => $data['user_id'] ?? null, // optional
        ];
        $commentModel->insert($commentData);

        return $this->respondCreated([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment_id' => $commentModel->insertID(),
        ]);
    }

    
}
