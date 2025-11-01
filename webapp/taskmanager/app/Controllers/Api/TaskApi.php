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

         $db = \Config\Database::connect();
        $builder = $db->table('tasks t');

         // subquery to get latest comment per task
        $latestCommentSub = '(SELECT c1.* FROM comments c1 JOIN (SELECT task_id, MAX(id) AS max_id FROM comments GROUP BY task_id) c2 ON c1.task_id = c2.task_id AND c1.id = c2.max_id) latest';

        $builder->select('t.*, u.name as user_name, d.name as department_name, tt.name as tasktype_name, 
            latest.comment as latest_comment, latest.user_id as latest_comment_user_id, latest.created_at as latest_comment_at');
        $builder->join('users u', 'u.id = t.user_id', 'left');
        $builder->join('departments d', 'd.id = t.department_id', 'left');
        $builder->join('tasktypes tt', 'tt.id = t.tasktype_id', 'left');
        // join latest comment (left join so tasks without comments still return)
        $builder->join($latestCommentSub, 'latest.task_id = t.id', 'left');

        // 1. AND filters
        $status = $this->request->getGet('status');
        $department_id = $this->request->getGet('department_id');
        $tasktype_id = $this->request->getGet('tasktype_id');
        $workweek_id = $this->request->getGet('workweek_id');
        $user_id = $this->request->getGet('user_id');

        if ($status) $builder->where('t.status', $status);
        if ($department_id) $builder->where('t.department_id', $department_id);
        if ($tasktype_id) $builder->where('t.tasktype_id', $tasktype_id);
        if ($workweek_id) $builder->where('t.workweek_id', $workweek_id);
        if ($user_id) $builder->where('t.user_id', $user_id);

        $statuses = $this->request->getGet('statuses'); // e.g. "Pending,In Progress"
        if ($statuses) {
            $statusArray = explode(',', $statuses);
            $builder->whereIn('t.status', $statusArray);
        }

        // 2. OR filters (user_id or assign_by)
        $or_filters = $this->request->getGet('or_filters'); // e.g. "user_id:5|assign_by:5"
        if ($or_filters) {
            $orArray = explode('|', $or_filters);
            $builder->groupStart();
            foreach ($orArray as $filter) {
                list($key, $value) = explode(':', $filter);
                $builder->orWhere("t.$key", $value);
            }
            $builder->groupEnd();
        }

        //log_message('debug', $builder->getCompiledSelect());

        // 3. Pagination
        $page = (int) $this->request->getGet('page') ?: 1;
        $perPage = (int) $this->request->getGet('per_page') ?: 10;

        $total = $builder->countAllResults(false); // false = don’t reset query
        $tasks = $builder->limit($perPage, ($page - 1) * $perPage)
                         ->get()
                         ->getResultArray();
        

        // 4. Send JSON response
        return $this->respond([
            'success' => true,
            'tasks' => $tasks,
            'pager' => [
                'currentPage' => $page,
                'perPage' => $perPage,
                'totalTasks' => $total,
                'totalPages' => ceil($total / $perPage)
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
        // If status is provided, update task status
        if (isset($data['status']) && trim($data['status']) != '') {
            $status = $data['status'];
            // Update task status
            $this->model->update($id, ['status' => $status]);
        }

        // If user_id is provided, update task user_id
        if (isset($data['assignUserId']) && trim($data['assignUserId']) != '') {
            $assignUserId = $data['assignUserId'];
            // Update task status
            $this->model->update($id, ['user_id' => $assignUserId]);
        }

        $commentModel = new CommentModel();
        $commentData = [
            'task_id'  => $id,
            'comment'  => $data['comment'],
            'user_id' => !empty($data['currentUserId']) ? $data['currentUserId'] : null,// optional
        ];
        $commentModel->insert($commentData);

        return $this->respondCreated([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment_id' => $commentModel->insertID(),
        ]);
    }

    
}
