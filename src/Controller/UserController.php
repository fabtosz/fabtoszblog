<?php

namespace FabtoszBlog\Controller;

use FabtoszBlog\Controller\AbstractController;
use FabtoszBlog\Repository\UserModel;
use FabtoszBlog\Entity\User;

class UserController extends AbstractController{
	
	public function addUser() {
		
		$userModel = new UserModel($this->di);
		
		$parameters = $this->request->getParams();
		//var_dump($parameters);
		if(empty($parameters)){
			//echo 'Wprowadź dane.';
		} else {
			$user = new User($this->request->getParams());
			//echo 'Zostały przesłane dane.';
			
			$stringPassword = $user->password;
			$hashPassword = password_hash($stringPassword, PASSWORD_DEFAULT, ['cost' => 12]);
			
			$user->password = $hashPassword;
			$userModel->insertUser($user);
			
		}
		
		$properties = ['title' => 'Rejestracja użytkownika'];
		return $this->render('user/add.twig', $properties);
	}
	
	public function loginPanel() {
		
		$isLogged = false;
		$parameters = $this->request->getParams();
		if(empty($parameters)){
			//echo 'Wprowadź dane.';
		} else {
			$userModel = new UserModel($this->di);
			
			$username = $parameters['username'];
			$stringPassword = $parameters['password'];
			$hashPassword = '';
			
			$user = $userModel->findUser($username);
			
			if($user != false) {
				$hashPassword = $user->password;
			} 
			
			$isLogged = password_verify($stringPassword, $hashPassword);

			if($isLogged) {
				$userId = $user->id;
				
				$this->request->getSession()->put('user_id', $userId);
				$this->request->getSession()->put('username', $username);
				$this->request->getSession()->flash('login_info', 'Zalogowano poprawnie.');
				
				$this->request->redirect('/');
				exit();
				
				
			} else {
				$this->request->getSession()->flash('login_info', 'Odmowa dostępu. Wprowadź poprawne dane logowania.');
			}
			
		}
		//var_dump($this->userId);
		$flash = $this->request->getSession()->flash('login_info');
		return $this->render('user/login.twig', [
			'flash' => $flash
		]);

	}
	
	public function logout() {
		if($this->request->getSession()->exists('user_id')){
			//unset($_SESSION['isLogged']);
			//Zamienić bo się zmieniła zależność $this->session->delete('isLogged');
			$this->request->getSession()->delete('user_id');
			//echo 'Wylogowano poprawnie';
			//header('Location: ' . '/');
			
			$this->request->getSession()->flash('login_info', 'Wylogowano poprawnie');
			$this->request->redirect('/');
		} else {
			//echo 'Najpierw musisz być zalogowany żeby się wylogować';
			$this->request->redirect('/');
		}
		
		
		return $this->render('user/login.twig', []);
		
		
	}
}