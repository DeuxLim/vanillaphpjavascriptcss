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

    public function update(Request $request){
        $task_completed = json_decode($request->all()["raw"], true)['task_completed'];
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
}