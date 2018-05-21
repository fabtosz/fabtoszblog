<?php

namespace FabtoszBlog\Utils;

class Validate {
	
	private $valid = false;
	private $errors = [];
	
	public function check($input, $items){
		
		
		foreach($items as $key => $value){
			
			foreach($value as $val_name => $val_value){
				if($val_name === 'required' && empty($input[$key])) {
					$this->addError("$key - to pole nie moze byc puste");
				}
				if($val_name === 'max' && (strlen($input[$key])) > $val_value) {
					$this->addError("$key - to pole jest za długie.");
				}
				if($val_name === 'min' && (strlen($input[$key])) < $val_value) {
					$this->addError("$key - to pole jest za krótkie.");
				}
			}
		}
		
		if(empty($this->errors)) {
			$this->valid = true;
		}
	}
	
	public function isValid() {
		return $this->valid;
	}
	
	private function addError($error) {
		$this->errors[] = $error;
	}
	
	public function getErrors(){
		return $this->errors;
	}

}