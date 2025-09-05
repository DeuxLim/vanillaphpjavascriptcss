<?php

namespace App\Controllers;

use Exception;

class Controller {
    // Descriptive generic status
    protected const RESPONSE_STATUSES = [
        "success" => "success",
        "error"   => "failed",
    ];

    // Descriptive generic messages
    protected const RESPONSE_MESSAGES = [
        "success" => "Request processed successfully.",
        "failed"  => "An error occurred while processing the request."
    ];

    public function sendJsonResponse(array $data = [], int $statusCode = 200): void
    {
        // Set headers
        http_response_code($statusCode);
        header('Content-Type: application/json');

        // Prepare payload
        $descriptiveStatus = ($statusCode >= 200 && $statusCode < 300) ? self::RESPONSE_STATUSES["success"] : self::RESPONSE_STATUSES["error"];
        $statusMessage = self::RESPONSE_MESSAGES[$descriptiveStatus];
        $payload = [
            "status" => $descriptiveStatus,
            "message" => $statusMessage,
            "data" => $data
        ];

        // Send response and exit
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function sendErrorJsonResponse(string $message, int $statusCode = 400, array $errors = [])
    {
        // Prepare payload
        $errorResponse = [
            "error_message" => $message
        ];

        if($errors){
            $errorResponse["errors"] = $errors;
        }
        
        // Send response through response handler
        $this->sendJsonResponse($errorResponse, $statusCode);    
    }
}