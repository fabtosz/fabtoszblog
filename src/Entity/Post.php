<?php

namespace FabtoszBlog\Entity;

class Post {
	
	public $id;
	public $title;
	public $content;
	public $author;
	public $publishedAt;
	public $categoryId;
	
	public function __construct($data = null){
		if(isset($data['id'])) {
			$this->id = $data['id'];
		}
		$this->title = $data['title'];
		$this->content = $data['content'];
		$this->author = $data['author'];
		//$this->publishedAt = $data['publishedAt'];
		//$this->categoryId = $data['categoryId'];
	} 

	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	public function getContent() {
		return $this->content;
	}
	
	public function setAuthor($author) {
		$this->author = $author;
	}
	public function getAuthor() {
		return $this->author;
	}
	
	public function setPublishedAt($publishedAt) {
		$this->publishedAt = $publishedAt;
	}
	public function getPublishedAt() {
		return $this->publishedAt;
	}
	
	public function setCategoryId($categoryId) {
		$this->categoryId = $categoryId;
	}
	public function getCategoryId() {
		return $this->categoryId;
	}
	

}