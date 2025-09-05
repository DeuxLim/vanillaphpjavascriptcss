<?php

namespace App\Controllers;

use App\Database;
use App\Request;
use PDO;

class TaskController {
    private PDO $DB;

    public function __construct()
    {
        $this->DB = Database::instance()->getConnection();
    }

    public function index(){
        $user_id = $_SESSION['user_id'];
        $query = $this->DB->prepare("SELECT * FROM tasks WHERE task_owner = :owner AND task_deleted != 1;");
        $query->execute(["owner" => $user_id]);
        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

        $total_tasks_count = count($tasks);
        $total_completed_count = count(array_filter($tasks, function ($task) {
            return $task['task_completed'] === 1;
        }));
        $total_pending_count = count(array_filter($tasks, function ($task) {
            return $task['task_completed'] === 0;
        }));

        header('Content-Type: application/json');
        echo json_encode([
            "tasks" => $tasks,
            "counts" => [
                "total" => $total_tasks_count,
                "completed" => $total_completed_count,
                "pending" => $total_pending_count
            ]
        ]);
        exit;
    }

    public function store(Request $request){
        // TODO:
        // - Add input validation

        $actual_request = $request->all();
        $task_title = $actual_request['task_title'];
        $task_description = $actual_request['task_description'];
        $task_priority = $actual_request['task_priority'];
        $task_owner = $_SESSION['user_id'];
        $task_due_unformatted = $actual_request['task_due'];
        $timestamp = strtotime($task_due_unformatted);
        $task_due = date("Y-m-d H:i", $timestamp);

        $query = $this->DB->prepare("INSERT INTO tasks (task_title, task_description, task_priority, task_owner, task_due) VALUES (:task_title, :task_description, :task_priority, :task_owner, :task_due)");
        $query->execute([
            ':task_title' => $task_title,
            ':task_description' => $task_description,
            ':task_priority' => $task_priority,
            ':task_owner' => $task_owner,
            ':task_due' => $task_due
        ]);

        // get the newly added task's ID
        $newTaskId = $this->DB->lastInsertId();

        header('Content-Type: application/json');
        if ($newTaskId !== "0") {
            echo json_encode([
                "status" => "success",
                "message" => "Task updated successfully",
                "task_id" => $newTaskId,
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No task was added",
            ]);
        }
    }

    public function show(Request $request){
        header("Content-Type: application/json");
        echo json_encode(["status" => "error", "message" => "This endpoint is currently not available"]);
    }

    public function update(Request $request){
        // WIP :: Insert input validation here 
        $updated_fields = json_decode($request->all()["raw"], true)['fields'];
        $taskId = $request->all()['__params']['id'];

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

        $finalParams = array_merge($updateParams, [
            ':task_owner' => $_SESSION['user_id'],
            ':task_id' => $taskId
        ]);
        $completeQuery = $query . $queryFields . $where;

        $query = $this->DB->prepare($completeQuery);
        $query->execute($finalParams);

        // Check how many rows were updated
        $rowsAffected = $query->rowCount();

        header('Content-Type: application/json');
        if ($rowsAffected > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Task updated successfully",
                "task_id" => $taskId
        ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No task was updated. Check task ID or owner.",
                "task_id" => $taskId
            ]);
        }
    }

    public function destroy(Request $request){
        $task_deleted = json_decode($request->all()["raw"], true)['fields']['task_deleted'];
        $task_id = $request->all()['__params']['id'];

        $query = $this->DB->prepare("UPDATE tasks SET task_deleted = :task_deleted WHERE task_id = :task_id and task_owner = :task_owner");
        $query->execute([
            ':task_deleted' => $task_deleted,
            ':task_id' => $task_id,
            ':task_owner' => $_SESSION['user_id'],
        ]);

        // Check how many rows were updated
        $rowsAffected = $query->rowCount();

        header('Content-Type: application/json');

        if ($rowsAffected > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Task deleted successfully",
                "task_id" => $task_id,
                "task_deleted" => $task_deleted
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No task was deleted. Check task ID or owner.",
                "task_id" => $task_id
            ]);
        }
    }
}