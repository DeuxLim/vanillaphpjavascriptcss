<?php

namespace App\Controllers;

use App\Database;
use App\Request;
use App\Controllers\Controller;
use App\Session;
use Exception;
use PDO;

class TaskController extends Controller {
    private PDO $DB;

    protected const TASK_DELETED = 1;

    protected const TASK_COMPLETED = 1;

    protected const MODIFIABLE_FIELDS = [
        "task_title",
        "task_description",
        "task_priority",
        "task_due",
        "task_completed",
        "task_deleted"
    ];

    protected const VALID_TASK_PRIORITIES = [
        "high",
        "medium",
        "low"
    ];

    public function __construct()
    {
        $this->DB = Database::instance()->getConnection();
    }

    public function index(){
        try{
            // Query all tasks
            $query = $this->DB->prepare("SELECT * FROM tasks WHERE task_owner = :owner AND task_deleted != 1;");
            $query->execute(["owner" => Session::getCurrentUser()]);
            $tasks = $query->fetchAll(PDO::FETCH_ASSOC);    

            // Calculate tasks total and per status
            $total_tasks_count = count($tasks);
            $total_completed_count = count(array_filter($tasks, function ($task) {
                return $task['task_completed'] === 1;
            }));
            $total_pending_count = $total_tasks_count - $total_completed_count;

            // Send response
            $this->sendJsonResponse([
                "tasks" => $tasks,
                "counts" => [
                    "total" => $total_tasks_count,
                    "completed" => $total_completed_count,
                    "pending" => $total_pending_count
                ]
            ], 201);
        } catch (Exception $e) {
            $this->sendErrorJsonResponse("Failed to retrieve tasks");
        }
    }

    public function store(Request $request){
        $input_request = $request->all();
        $actual_request = array_filter($input_request, function ($key) {
            return in_array($key, self::MODIFIABLE_FIELDS);
        }, ARRAY_FILTER_USE_KEY);

        // Validate user inputs
        $errors = [];
        if (empty($actual_request['task_title'])) {
            $errors['task_title'] = "Task title is required.";
        }
        if (empty($actual_request['task_description'])) {
            $errors['task_description'] = "Task description is required.";
        }
        if (!isset($actual_request['task_priority']) || !in_array($actual_request['task_priority'], self::VALID_TASK_PRIORITIES)) {
            $errors['task_priority'] = "Task priority must be low, medium, or high.";
        }
        if (empty($actual_request['task_due']) || strtotime($actual_request['task_due']) === false) {
            $errors['task_due'] = "Task due date is invalid or missing.";
        }

        if ($errors) {
            $this->sendErrorJsonResponse("Form input has errors", 422, $errors);
        }
    
        // Prepare inputs
        $task_title = trim($actual_request['task_title']);
        $task_description = trim($actual_request['task_description']);
        $task_priority = trim($actual_request['task_priority']);
        $task_owner = Session::getCurrentUser();

        // Handle date input - task_due
        $task_due_unformatted = $actual_request['task_due'];
        $timestamp = strtotime($task_due_unformatted);
        $task_due = date("Y-m-d H:i", $timestamp);

        // Save task
        $query = $this->DB->prepare("INSERT INTO tasks (task_title, task_description, task_priority, task_owner, task_due) VALUES (:task_title, :task_description, :task_priority, :task_owner, :task_due)");
        $query->execute([
            ':task_title' => $task_title,
            ':task_description' => $task_description,
            ':task_priority' => $task_priority,
            ':task_owner' => $task_owner,
            ':task_due' => $task_due
        ]);
        $newTaskId = $this->DB->lastInsertId();

        // Response
        if(!$newTaskId){
            $this->sendErrorJsonResponse("No task was added", 500);
        }
        $this->sendJsonResponse(["task_id" => $newTaskId], 201);
    }

    public function show(Request $request){
        $this->sendErrorJsonResponse("This endpoint is currently under development.", 404);
    }

    public function update(Request $request){
        $updated_fields = json_decode($request->all()["raw"], true)['fields'];
        $task_id = $this->getTaskIdFromParams($request);

        // WIP :: Insert input validation here 
        // Logic...

        // Prepare update query
        $query = "UPDATE tasks SET ";
        $queryFields = "";
        $where = " WHERE task_owner = :task_owner AND task_id = :task_id";
        $last_key = array_key_last($updated_fields);
        $updateParams = [];
        foreach($updated_fields as $field => $value){
            if($field === "task_completed" && $value){
                $updateParams["task_completed_date"] = date("Y-m-d H:i");
                $queryFields .= "task_completed_date = :task_completed_date, ";
            }

            $queryFields .= "$field = :$field";
            if($last_key !== $field){
                $queryFields .= ", ";
            }

            $updateParams[":$field"] = is_bool($value) ? (int)$value : $value;
        }

        // Execute query
        $finalParams = array_merge($updateParams, [
            ':task_owner' => Session::getCurrentUser(),
            ':task_id' => $task_id
        ]);
        $completeQuery = $query . $queryFields . $where;
        $query = $this->DB->prepare($completeQuery);
        $query->execute($finalParams);

        // Response
        $rowsAffected = $query->rowCount();
        if($rowsAffected === 0){
            $this->sendErrorJsonResponse("No task was updated");
        }
        $this->sendJsonResponse(["task_id" => $task_id]);
    }

    public function destroy(Request $request){
        $task_deleted = json_decode($request->all()["raw"], true)['fields']['task_deleted'];
        $task_id = $this->getTaskIdFromParams($request);

        // Delete task
        $query = $this->DB->prepare("UPDATE tasks SET task_deleted = :task_deleted WHERE task_id = :task_id and task_owner = :task_owner");
        $query->execute([
            ':task_deleted' => $task_deleted,
            ':task_id' => $task_id,
            ':task_owner' => Session::getCurrentUser(),
        ]);

        // Response
        $rowsAffected = $query->rowCount();
        if($rowsAffected === 0){
            $this->sendErrorJsonResponse("No task was deleted.");
        }
        $this->sendJsonResponse(["task_id" => $task_id]);
    }

    // ===== PRIVATE HELPER METHODS =====
    public function getTaskIdFromParams(Request $request){
        $params = $request->all();
        $taskId = $params['__params']['id'] ?? null;
        
        return $taskId ? (int)$taskId : null;
    }
}