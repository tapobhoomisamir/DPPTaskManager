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
    protected $allowedFields    = ['project_id','user_id','title','description','department_id','assign_by','due_date','tasktype_id','workweek_id','status','priority','private','start_date','due_date','status','completed_date','expense','time_taken','is_deleted'];

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
        $builder = $this->select('tasks.id,tasks.title, tasks.status, tasks.due_date, tasks.completed_date,tasks.due_date,tasks.time_taken,tasks.expense,users.name as user_name,departments.name as department_name,assign_by_user.name as assign_by_name,tasktypes.name as tasktype_name,workweeks.workweek as workweek_name')
            ->join('users', 'users.id = tasks.user_id', 'left')
            ->join('users as assign_by_user', 'assign_by_user.id = tasks.assign_by', 'left')
            ->join('departments', 'departments.id = tasks.department_id', 'left')
            ->join('tasktypes', 'tasktypes.id = tasks.tasktype_id', 'left')
            ->join('workweeks', 'workweeks.id = tasks.workweek_id', 'left');


        if (!empty($filters['status'])) $builder->where('tasks.status', $filters['status']);
        if (!empty($filters['department_id'])) $builder->where('tasks.department_id', $filters['department_id']);
        if (!empty($filters['tasktype_id'])) $builder->where('tasks.tasktype_id', $filters['tasktype_id']);
        if (!empty($filters['workweek_id'])) $builder->where('tasks.workweek_id', $filters['workweek_id']);

        $builder->where('tasks.is_deleted', 0); // Exclude deleted tasks

        if(!empty($filters['or_filters'])){

            // 2. OR filters (user_id or assign_by)
            $or_filters = $filters['or_filters']; // e.g. "user_id:5|assign_by:5"
            if ($or_filters) {
                $orArray = explode('|', $or_filters);
                $builder->groupStart();
                foreach ($orArray as $filter) {
                    list($key, $value) = explode(':', $filter);
                    $builder->orWhere("tasks.$key", $value);
                }
                $builder->groupEnd();
            }
        }

        return $builder; // Return the builder, NOT findAll()
    }
    
}
