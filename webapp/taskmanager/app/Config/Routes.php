<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/reports', 'Report::index');

$routes->get('/tasks', 'Task::index');
$routes->get('/tasks/create', 'Task::create');
$routes->post('/tasks/store', 'Task::store');
$routes->get('/tasks/updateStatus/(:num)', 'Task::updateStatus/$1');

$routes->get('/tasks/view/(:num)', 'Task::view/$1');
$routes->get('/tasks/edit/(:num)', 'Task::edit/$1');


$routes->get('/tasks/exportXls', 'Task::exportXls');
$routes->get('/tasks/download/pdf', 'Task::downloadPdf');

$routes->post('/tasks/uploadAttachment/(:num)', 'Task::uploadAttachment/$1');

$routes->get('/project', 'Project::index');

$routes->get('no-access', 'AuthController::noAccess');

//$routes->get('api/reports/department-tasks', 'Api\ReportApi::departmentTasks');


$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes){
    $routes->get('tasks', 'TaskApi::index');        // fetch all tasks
    $routes->get('tasks/(:num)', 'TaskApi::show/$1');  // fetch single task
    $routes->post('tasks', 'TaskApi::create');     // create task
    $routes->put('tasks/(:num)', 'TaskApi::update/$1'); // update task
    $routes->delete('tasks/(:num)', 'TaskApi::delete/$1'); // delete task

    $routes->put('tasks/(:num)/status', 'TaskApi::updateStatus/$1');
    $routes->post('tasks/(:num)/comments', 'TaskApi::addComment/$1');

    $routes->get('reports/department-agenda-distribution', 'ReportApi::departmentAgendaDistribution');
    $routes->get('reports/agenda-status-distribution', 'ReportApi::agendaStatusDistribution');
    $routes->get('reports/worklist-trend', 'ReportApi::worklistTrend');
});

