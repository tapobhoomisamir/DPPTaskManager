<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['project_id','user_id','title','description','department_id','assign_by','due_date','tasktype_id','workweek_id','status','start_date','due_date','status','completed_date','expense','time_taken'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getTasksWithFiltered($filters = [])
    {
        $builder = $this->select('tasks.id,tasks.title, tasks.status, tasks.due_date, users.name as user_name,departments.name as department_name,assign_by_user.name as assign_by_name,tasktypes.name as tasktype_name')
            ->join('users', 'users.id = tasks.user_id', 'left')
            ->join('users as assign_by_user', 'assign_by_user.id = tasks.assign_by', 'left')
            ->join('departments', 'departments.id = tasks.department_id', 'left')
            ->join('tasktypes', 'tasktypes.id = tasks.tasktype_id', 'left');

        if (!empty($filters['status'])) {
            $builder->where('tasks.status', $filters['status']);
        }
        if (!empty($filters['user_id'])) {
            $builder->where('tasks.user_id', $filters['user_id']);
        }
        if (!empty($filters['due_date'])) {
            $builder->where('tasks.due_date', $filters['due_date']);
        }
        if (!empty($filters['department_id'])) {
            $builder->where('tasks.department_id', $filters['department_id']);
        }
        if (!empty($filters['tasktype_id'])) {
            $builder->where('tasks.tasktype_id', $filters['tasktype_id']);
        }
        if (!empty($filters['workweek_id'])) {
            $builder->where('tasks.workweek_id', $filters['workweek_id']);
        }

        return $builder; // Return the builder, NOT findAll()
    }
    
}
