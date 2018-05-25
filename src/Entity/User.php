<?php

namespace FabtoszBlog\Entity;

class User {
	
	public $id;
	public $username;
	public $password;
	public $name;
	public $group_id;
	
	public function __construct($data = null){
		if(isset($data['id'])) {
			$this->id = $data['id'];
		}
		$this->username = $data['username'];
		$this->password = $data['password'];
		$this->name = $data['name'];
		$this->group_id = $data['group_id'];
	} 

}