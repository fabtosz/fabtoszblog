<?php

namespace FabtoszBlog\Entity;

class Comment {
	
	public $id;
	public $content;
	public $author;
	public $vote;
	public $publishedAt;
	public $postId;
	
	public function __construct($data = null){
		if(isset($data['id'])) {
			$this->id = $data['id'];
		}
		$this->content = $data['content'];
		$this->author = $data['author'];
	} 
	
	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
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

	public function setVote($vote) {
		$this->vote = $vote;
	}
	public function getVote() {
		return $this->vote;
	}	
	
	public function setPublishedAt($publishedAt) {
		$this->publishedAt = $publishedAt;
	}
	public function getPublishedAt() {
		return $this->publishedAt;
	}	
	
	public function setPostId($postId) {
		$this->postId = $postId;
	}
	public function getPostId() {
		return $this->postId;
	}	
}