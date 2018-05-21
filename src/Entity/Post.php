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
		//$this->categoryId = $data['category_id'];
	} 

}