<?php

namespace FabtoszBlog\Controller;

//use FabtoszBlog\Controller\AbstractController;
use FabtoszBlog\Repository\PostModel;
use FabtoszBlog\Repository\CommentModel;
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
	
	public function comments() {
		
		$commentModel = new CommentModel($this->di);
		
		$page = ($this->request->getParams()['page']) ?? 1;
	
		$pagination = $commentModel->getCommentsByPage($page, 5);
		
		$flash = $this->request->getSession()->flash('post_info');
		//Zrobić jebany paginator
		return $this->render('admin/comments/comments.twig', [
			'page_number' => $page,
			'comments' => $pagination['records'],
			'pages_count' => $pagination['pages_count'],
			'flash' => $flash
		]);
	}
	
	public function showPost($id) {
		$postModel = new PostModel($this->di);
		$post = $postModel->getPost($id);
		
		$commentModel = new CommentModel($this->di);
		$comments = $commentModel->getAllCommentsByPostId($id);
		$post->setComments($comments);
		
		return $this->render('admin/posts/show.twig', [
			'post' => $post
		]);
		
	}

}