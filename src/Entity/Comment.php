<?php

namespace FabtoszBlog\Entity;

class Comment {
	
	public $id;
	public $comment;
	public $author;
	public $publishedAt;
	public $vote;
	public $postId;
	
	public function __construct($data = null){
		if(isset($data['id'])) {
			$this->id = $data['id'];
		}
		$this->comment = $data['comment'];
		$this->author = $data['author'];
		//$this->publishedAt = $data['publishedAt'];
		//$this->vote = $data['vote'];
		//$this->postId;
	} 

}