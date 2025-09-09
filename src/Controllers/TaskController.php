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
        "task_deleted",
        "task_completed_date"
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
            exit();

        } catch (Exception $e) {
            $this->sendErrorJsonResponse("Failed to retrieve tasks", 500);
            exit();
        }
    }

    public function store(Request $request){
        $input_request = json_decode($request->all()['raw'], true);
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
            exit();
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
            exit();
        }

        $this->sendJsonResponse(["task_id" => $newTaskId], 201);
        exit();
    }

    public function show(Request $request){
        $this->sendErrorJsonResponse("This endpoint is currently under development.", 404);
        exit();
    }

    public function update(Request $request){
        try{
            $updated_fields = json_decode($request->all()["raw"], true);
            $updated_fields = array_map('trim', $updated_fields);
            $task_id = $this->getTaskIdFromParams($request);
            $errors = [];

            // Validate task_id exists
            if (!$task_id) {
                $this->sendErrorJsonResponse("Invalid or missing task ID.", 400);
                exit();
            }

            // Validate each field
            foreach($updated_fields as $field => $value) {
                // Check if field is allowed
                if (!in_array($field, self::MODIFIABLE_FIELDS)) {
                    $errors[$field] = "Field '$field' is not allowed to be updated.";
                }

                // Field-specific validation
                switch($field) {
                    case 'task_title':
                        if (!is_string($value) || empty(trim($value)) || strlen($value) > 255) {
                            $errors[$field] = "Task title must be a non-empty string (max 255 chars).";
                        }
                        break;
                        
                    case 'task_description':
                        if (!is_string($value) || strlen($value) > 1000) {
                            $errors[$field] = "Task description must be a string (max 1000 chars).";
                        }
                        break;
                        
                    case 'task_completed':
                        if (!in_array($value, [0, 1, "0", "1"], true)) {
                            $errors[$field] = "Task completed must be 0 or 1.";
                        }
                        break;
                        
                    case 'task_priority':
                        $validPriorities = ['low', 'medium', 'high'];
                        if (!in_array($value, $validPriorities)) {
                            $errors[$field] = "Task priority must be: low, medium, or high.";
                        }
                        break;
                        
                    case 'task_due_date':
                        if ($value !== null && !strtotime($value)) {
                            $errors[$field] = "Invalid date format for due date.";
                        }
                        break;
                }
            }

            if(!empty($errors)){
                $this->sendErrorJsonResponse("Task update request contains invalid inputs", 400, $errors);
                exit();
            }

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
                $this->sendErrorJsonResponse("No changes were made.", 200);
                exit();
            }
        
            $this->sendJsonResponse(["task_id" => $task_id], 200, "Task updated.");
        } catch (Exception $e) {
            $this->sendErrorJsonResponse("Failed to update task.", 500);
        }
    }

    public function destroy(Request $request){
        $task_deleted = json_decode($request->all()["raw"], true)['task_deleted'];
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
            $this->sendErrorJsonResponse("No task was deleted.", 400);
            exit();
        }

        $this->sendJsonResponse(["task_id" => $task_id], 200, "Task deleted.");
        exit();
    }

    // ===== PRIVATE HELPER METHODS =====
    public function getTaskIdFromParams(Request $request){
        $params = $request->all();
        $taskId = $params['__params']['id'] ?? null;
        
        return $taskId ? (int)$taskId : null;
    }
}