<?php

namespace FabtoszBlog\Utils;

class Request {
    const GET = 'GET';
    const POST = 'POST';

    private $domain;
    private $path;
    private $method;
    private $params;
    private $cookies;
	private $session;

    public function __construct(Session $session) {
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = array_merge($_POST, $_GET);
        $this->cookies = $_COOKIE;
		$this->session = $session;
    }

    public function getUrl(): string {
        return $this->domain . $this->path;
    }

    public function getDomain(): string {
        return $this->domain;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function isPost(): bool {
        return $this->method === self::POST;
    }

    public function isGet(): bool {
        return $this->method === self::GET;
    }

    public function getParams() {
        return $this->params;
    }

    public function getCookies() {
        return $this->cookies;
    }

	public function getSession() {
		return $this->session;
	}
	
	public function redirect(string $location) {
		header('Location: ' . $location);
	}
}