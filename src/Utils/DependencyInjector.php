<?php

namespace FabtoszBlog\Utils;

class DependencyInjector {
	
	private $dependencies = [];
	
	public function set(string $name, $object){
		$this->dependencies[$name] = $object;
	}
	
	public function get(string $name) {
		if(isset($this->dependencies[$name])) {
			return $this->dependencies[$name];
		}
	}
}