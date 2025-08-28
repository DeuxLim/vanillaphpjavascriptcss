<?php

namespace App;

class Request {
    protected array $post;
    protected array $get;
    protected array $server;
    protected array $cookie;
    protected array $files;
    protected array $env;
    protected string $raw;
    protected array $jsonBody;

    public function __construct(?array $post = null, ?array $get = null, ?array $server = null, ?array $cookie = null, ?array $files = null, ?array $env = null, ?string $raw = null)
    {
        $this->post = $post ?? $_POST;
        $this->get = $get ?? $_GET;
        $this->server = $server ?? $_SERVER;
        $this->cookie = $cookie ?? $_COOKIE;
        $this->files = $files ?? $_FILES;
        $this->env = $env ?? $_ENV;
        $this->raw = $raw ?? file_get_contents('php://input');

        if($this->responseIsJson()){
            $this->jsonBody = json_decode($this->raw, true) ?? [];
        }
    }

    public function all(){
        return array_merge(
            $this->post,
            $this->get,
            $this->server,
            $this->cookie,
            $this->files,
            $this->env,
            ["raw" => $this->raw]
        );
    }

    public function post(){
        return $this->post;
    }

    private function responseIsJson(): bool {
        return isset($this->server['CONTENT_TYPE']) &&
               stripos($this->server['CONTENT_TYPE'], 'application/json') !== false;
    }

    public function validate(){
        
    }
}