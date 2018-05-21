<?php

namespace FabtoszBlog\Controller;

use FabtoszBlog\Controller\AbstractController;
use FabtoszBlog\Repository\PostModel;
use FabtoszBlog\Repository\Paginator;

class DefaultController extends AbstractController{
	
	public function admin() {
		return $this->render('/admin/panel.twig', []);
	}
	
	public function index() {
		
		$flash = $this->request->getSession()->flash('login_info');
		
		$postModel = new PostModel($this->di);
		
		$page = ($this->request->getParams()['page']) ?? 1;
		$pagination = $postModel->getPostsByPage($page, 7);
		
		return $this->render('base.twig', [
			'flash' => $flash,
			'posts' => $pagination['records'],
			'pages_count' => $pagination['pages_count'],
			'page' => $page
		]);
		
	}
}