<?php namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateWorkweeks extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'generate:workweeks';
    protected $description = 'Generate next 12 workweeks automatically.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('workweeks');
        $builder->selectMax('end_date');
        $query = $builder->get()->getRowArray();

        $lastEndDate = $query['end_date'] ? new \DateTime($query['end_date']) : new \DateTime();
        $startDate = clone $lastEndDate;
        $startDate->modify('+1 day');

        for ($i = 0; $i < 12; $i++) {
            $endDate = clone $startDate;
            $endDate->modify('+6 days');
            $month = $startDate->format('F');
            $workweek = $startDate->format('d-M-Y') . ' to ' . $endDate->format('d-M-Y');

            $data = [
                'workweek'   => $workweek,
                'month'      => $month,
                'created_at' => date('Y-m-d H:i:s'),
                'start_date' => $startDate->format('Y-m-d 00:00:00'),
                'end_date'   => $endDate->format('Y-m-d 00:00:00'),
            ];

            $db->table('workweeks')->insert($data);
            CLI::write("Inserted: {$startDate->format('Y-m-d 00:00:00')}", 'green');

            $startDate->modify('+7 days');
        }
    }
}
