<?php

namespace FabtoszBlog\Controller;

use FabtoszBlog\Utils\DependencyInjector;
use FabtoszBlog\Utils\Request;

class AbstractController {
	protected $di;
	
	protected $request;
	protected $db;
	protected $view;
	protected $validator;
	protected $userId;
	protected $username;
	
	public function __construct(DependencyInjector $di, Request $request) {
		$this->di = $di;
		$this->view = $di->get('view');
		$this->db = $di->get('PDO');
		$this->request = $request;
		$this->validator = $di->get('validator');
	}
	
	protected function render(string $template, array $params): string {
		if($this->request->getSession()->exists('user_id')) {
			//parametry po zalogowaniu - dostępne w każdym kontrolerze
			$params['userId'] = $this->request->getSession()->get('user_id');
			$params['username'] = $this->request->getSession()->get('username');
		}
		
        return $this->view->loadTemplate($template)->render($params);
    }
	
	public function setUserId($id) {
        $this->userId = $id;
    }
	
	public function getUserId() {
        return $this->userId;
    }
	
}