<?php

namespace App\Controllers;

use App\Models\TaskModel; 
use App\Models\CommentModel; 
use App\Models\AttachmentModel; 
use App\Models\DepartmentModel; 
use App\Models\TaskTypeModel; 
use App\Models\UserModel;
use App\Models\WorkWeekModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Dompdf\Dompdf;
use Dompdf\Options;

class Report extends BaseController
{
    public function index() {
        $taskModel = new TaskModel();
        
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


        if ($this->sessionUser['role'] != 'Administrator' &&  $this->sessionUser['role'] != 'Authority' && $this->sessionUser['role'] != 'Incharge')
        {
            return redirect()->to(base_url('no-access'));
        }

        return view('reports/index', [
            'tasks' => $tasks,
            'departments' => $departments,
            'tasktypes' => $tasktypes,
            'users'       => $users,
            'workweeks' => $workweeks,
            'sessionUser' => $this->sessionUser,
        ]);
    }
}