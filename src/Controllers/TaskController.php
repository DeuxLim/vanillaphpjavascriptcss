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
        $query = $this->DB->prepare("SELECT * FROM tasks WHERE task_owner = :owner;");
        $query->execute(["owner" => $user_id]);
        $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($tasks);
        exit;
    }

    public function store(Request $request){
        // TODO:
        // - Implement task due date handling
        // - Add input validation

        $actual_request = $request->all();
        $task_title = $actual_request['task_title'];
        $task_description = $actual_request['task_description'];
        $task_priority = $actual_request['task_priority'];
        $task_owner = $_SESSION['user_id'];

        $query = $this->DB->prepare("INSERT INTO tasks (task_title, task_description, task_priority, task_owner) VALUES (:task_title, :task_description, :task_priority, :task_owner)");
        $query->execute([
            ':task_title' => $task_title,
            ':task_description' => $task_description,
            ':task_priority' => $task_priority,
            ':task_owner' => $task_owner
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
        dd($request);
    }

    public function update(Request $request){
        $task_completed = json_decode($request->all()["raw"], true)['fields']['task_completed'];
        $taskId = $request->all()['__params']['id'];

        $query = $this->DB->prepare("UPDATE tasks SET task_completed = :task_completed WHERE task_owner = :task_owner AND task_id = :task_id");
        $query->execute([
            ':task_completed' => !empty($task_completed) ? $task_completed : 0,
            ':task_owner' => $_SESSION['user_id'],
            ':task_id' => $taskId
        ]);

        // Check how many rows were updated
        $rowsAffected = $query->rowCount();

        header('Content-Type: application/json');

        if ($rowsAffected > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Task updated successfully",
                "task_id" => $taskId,
                "task_completed" => $task_completed
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
        dd($request);
    }
}