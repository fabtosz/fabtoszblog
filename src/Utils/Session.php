<?php

namespace FabtoszBlog\Utils;

class Session {
	
	public function get($name) {
		return $_SESSION[$name];
	}
	
	public function put($name, $value) {
		return $_SESSION[$name] = $value;
	}
	
	public function exists($name) {
		return isset($_SESSION[$name]) ? true : false;
	}
	
	public function delete($name) {
		if ($this->exists($name)) {
			unset($_SESSION[$name]);
		}
	}
	
	public function flash($name, $string = null){
		if($this->exists($name) && $string === null) {
			$flashMessage = $this->get($name);
			$this->delete($name);
			return $flashMessage;
		} else {
			$this->put($name, $string);
		}
	}
	
}