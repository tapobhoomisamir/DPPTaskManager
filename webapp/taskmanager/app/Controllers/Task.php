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

class Task extends BaseController
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
        $attachmentModel = new AttachmentModel();

       

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

        $attachments = $attachmentModel
            ->where('task_id', $id)
            ->orderBy('uploaded_at', 'DESC')
            ->findAll();

        return view('tasks/view', [
            'task' => $task,
            'comments' => $comments,
            'attachments' => $attachments,
        ]);
    }

    

    // Edit form page
    public function edit($id)
    {
        $taskModel = new TaskModel();
        $commentModel = new CommentModel();
        $attachmentModel = new AttachmentModel();

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

        $attachments = $attachmentModel
            ->where('task_id', $id)
            ->orderBy('uploaded_at', 'DESC')
            ->findAll();

        return view('tasks/edit', [
            'task' => $task,
            'departments' => $departments,
            'tasktypes' => $tasktypes,
            'users'       => $users,
            'workweeks' => $workweeks,
            'comments' => $comments,
            'attachments' => $attachments,
        ]);
    }

    public function exportXls()
    {
        $taskModel = new TaskModel();
        $filters = $this->request->getGet(); // capture applied filters

        // fetch all tasks (ignoring pagination but applying filters)
        $builder = $taskModel->getTasksWithFiltered($filters); 
        $tasks = $builder->get()->getResultArray();
        // 👆 Make sure your model method allows "false" for no pagination.

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // Header row
        $sheet->setCellValue('A1', 'Title');
        $sheet->setCellValue('B1', 'Department');
        $sheet->setCellValue('C1', 'Type');
        $sheet->setCellValue('D1', 'User');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Work week');
        $sheet->setCellValue('G1', 'Completed Date');
        $sheet->setCellValue('H1', 'Expense Amount');
        $sheet->setCellValue('I1', 'Due Date');
        $sheet->setCellValue('J1', 'Time Taken (hrs)');

        $row = 2;
        foreach ($tasks as $task) {
            $sheet->setCellValue('A' . $row, $task['title']);
            $sheet->setCellValue('B' . $row, $task['department_name']);
            $sheet->setCellValue('C' . $row, $task['tasktype_name']);
            $sheet->setCellValue('D' . $row, $task['user_name']);
            $sheet->setCellValue('E' . $row, $task['status']);
            $sheet->setCellValue('F' . $row, $task['workweek_name']);
            $sheet->setCellValue('G' . $row, $task['completed_date']);
            $sheet->setCellValue('H' . $row, $task['expense']);
            $sheet->setCellValue('I' . $row, $task['due_date']);
            $sheet->setCellValue('J' . $row, $task['time_taken']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        // Send file as response
        $filename = 'tasks_report_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function uploadAttachment($taskId)
    {
        $file = $this->request->getFile('attachment');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create a folder if not exists
            $uploadPath = FCPATH . 'uploads/tasks/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Save with random name
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Save file info in DB

            $db = db_connect();
            $db->table('attachments')->insert([
                'task_id'   => $taskId,
                'user_id'   => 1, // Replace with actual user ID from session
                'file_name' => $file->getClientName(),
                'file_path' => 'uploads/tasks/' . $newName
            ]);

            return redirect()->back()->with('message', 'File uploaded successfully!');
        }

        return redirect()->back()->with('error', 'File upload failed.');
    }

}

