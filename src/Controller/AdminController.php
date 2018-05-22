<?php

namespace FabtoszBlog\Controller;

//use FabtoszBlog\Controller\AbstractController;
use FabtoszBlog\Repository\PostModel;
//use FabtoszBlog\Repository\Paginator;

class AdminController extends AbstractController{
	
	public function panel() {
		return $this->render('admin/panel.twig', []);
	}
	
	public function posts() {
		
		$postModel = new PostModel($this->di);
		
		$page = ($this->request->getParams()['page']) ?? 1;
		$pagination = $postModel->getPostsByPage($page, 5);
		
		$flash = $this->request->getSession()->flash('post_info');
		//Zrobić jebany paginator
		return $this->render('admin/posts/posts.twig', [
			'page_number' => $page,
			'posts' => $pagination['records'],
			'pages_count' => $pagination['pages_count'],
			'flash' => $flash
		]);
	}

}