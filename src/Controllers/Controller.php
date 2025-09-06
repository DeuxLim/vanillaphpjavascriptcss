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

    public function sendJsonResponse(array $data = [], int $statusCode = 200, ? string $message = null): void
    {
        // Set headers
        http_response_code($statusCode);
        header('Content-Type: application/json');

        // Prepare payload
        $descriptiveStatus = ($statusCode >= 200 && $statusCode < 300) ? self::RESPONSE_STATUSES["success"] : self::RESPONSE_STATUSES["error"];
        $statusMessage = $message ? $message : self::RESPONSE_MESSAGES[$descriptiveStatus];
        $payload = [
            "status" => $descriptiveStatus,
            "message" => $statusMessage,
        ];

        if (empty($data['error'])) {
            $payload['data'] = $data;
        } else {
            $payload = array_merge($payload, $data);
        }

        // Send response and exit
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public function sendErrorJsonResponse(string $message, int $statusCode = 400, array $errors = [])
    {
        // Prepare payload
        $errorResponse = [
            "error" => array_filter([ // array_filter without callback removes null values
                "message" => $message,
                "details" => $errors ?: null
            ])
        ];
        
        // Send response through response handler
        $this->sendJsonResponse($errorResponse, $statusCode);    
    }
}