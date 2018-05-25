<?php

namespace FabtoszBlog\Controller;

use FabtoszBlog\Controller\AbstractController;
use FabtoszBlog\Repository\PostModel;
use FabtoszBlog\Entity\Post;
use FabtoszBlog\Repository\CommentModel;
use FabtoszBlog\Entity\Comment;

class PostController extends AbstractController{
	
	public function deletePost($id) {
		$postModel = new PostModel($this->di);
		$postModel->deletePost($id);
		
		$this->request->getSession()->flash('post_info', "Post został usunięty.");
		
		$this->request->redirect('/admin/posts');
	}
	
	public function showPostWithId($id) {
		
		$postModel = new PostModel($this->di);
		$post = $postModel->getPost($id);
		
		if($this->request->isPost()){
			
			$formData = $this->request->getParams();
			//var_dump($formData);die;
			$validator = $this->validator;
			$validator->check($formData, array(
				  'comment'   => array(
					'required' => true,
					'max'      => 50
				  ),
				  'author'   => array(
					'required' => true,
					'max'      => 50
				  )
			));
		
			if($validator->isValid()) {
				$comment = new Comment($formData);
				$comment->publishedAt = date("Y-m-d H:i:s");
				$comment->postId = $id;
				
				$commentModel = new CommentModel($this->di);
				$commentModel->insertComment($comment);
				
				$this->request->getSession()->flash('comment_info', "Komentarz został dodany.");
				
			} else {
				$errors = $validator->getErrors();
				
				
				$properties['errors'] = $errors;
				return $this->render('post/show.twig', $properties);
			}

		} 
		
		$commentModel = new CommentModel($this->di);
		$comments = $commentModel->getAllCommentsByPostId($id);
		$post->setComments($comments);
		
		$flash = $this->request->getSession()->flash('comment_info');
		
		return $this->render('post/show.twig', [
			'post' => $post,
			'flash' => $flash
		]);
	}
	
	public function addPost() {		
		
		if($this->request->isPost()){
			
			$formData = $this->request->getParams();
			
			$validator = $this->validator;
			$validator->check($formData, array(
				  'title'   => array(
					'required' => true,
					'max'      => 150
				  ),
				  'content'       => array(
					'required' => true
				  ),
				  'author'   => array(
					'required' => true,
					'max'      => 50
				  ),
				  'categoryId'   => array(
					'required' => true,
					'max'      => 20
				  ),
			));
		
			if($validator->isValid()) {
				$post = new Post($formData);
				$post->setPublishedAt(date("Y-m-d H:i:s"));
				
				$postModel = new PostModel($this->di);
				$postModel->insertPost($post);
				
				$postId = $this->db->lastInsertId();
				
				$this->request->getSession()->flash('post_info', "Post został dodany do bazy. Możesz go przycztać <a href=\"/post/$postId\">tutaj</a>.");
				
			} else {
				$errors = $validator->getErrors();
				return $this->render('post/add.twig', [
					'errors' => $errors
				]);
				
			}

		} 
		
		$flash = $this->request->getSession()->flash('post_info');
		return $this->render('post/add.twig', [
			'flash' => $flash
		]);
		
	}
	
	public function editPost($id) {		
		
		$postModel = new PostModel($this->di);
		$post = $postModel->getPost($id);
		$postId = $post->getId();
		
		if($this->request->isPost()){
			
			$formData = $this->request->getParams();
			
			$validator = $this->validator;
			$validator->check($formData, array(
				  'title'   => array(
					'required' => true,
					'max'      => 150
				  ),
				  'content'       => array(
					'required' => true
				  ),
				  'author'   => array(
					'required' => true,
					'max'      => 50
				  ),
				  'categoryId'   => array(
					'required' => true,
					'max'      => 20
				  ),
			));
		
			if($validator->isValid()) {
				
				$post->setTitle($formData["title"]);
				$post->setContent($formData["content"]);
				$post->setAuthor($formData["author"]);
				$post->setCategoryId($formData["categoryId"]);
				
				
				$postModel->updatePost($post);
				
				$this->request->getSession()->flash('post_info', "Post został zaktualizowany. ");
				
			} else {
				$errors = $validator->getErrors();
				return $this->render('post/edit.twig', [
					'errors' => $errors,
					'post' => $post
				]);
				
				
			}

		} 
		
		$flash = $this->request->getSession()->flash('post_info');
		return $this->render('post/edit.twig', [
			'flash' => $flash,
			'post' => $post
		]);
		
	}
	

}