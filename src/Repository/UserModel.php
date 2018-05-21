<?php

namespace FabtoszBlog\Repository;

use FabtoszBlog\Repository\AbstractModel;
use FabtoszBlog\Entity\User;
use PDO;

class UserModel extends AbstractModel {
	
	public function insertUser(User $user){
		// If the ID is set, we're updating an existing record
        if (isset($user->id)) {
            return $this->updateUser($user);
        }
        $stmt = $this->db->prepare('
            INSERT INTO users 
                (username, password, name, group_id) 
            VALUES 
                (:username, :password, :name, :group_id)
        ');
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':name', $user->name);
		$stmt->bindParam(':group_id', $user->group_id);
		
        return $stmt->execute();
	}
	
	public function updateUser(User $user){
		if (!isset($user->id)) {
            // We can't update a record unless it exists...
            throw new \Exception(
                'Cannot update post that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE users
            SET username = :username,
                password = :password,
                name = :name,
                group_id = :group_id
            WHERE id = :id
        ');
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':name', $user->name);
		$stmt->bindParam(':group_id', $user->group_id);
        $stmt->bindParam(':id', $user->id);
        return $stmt->execute();
	}
	
	public function deleteUser($id){
		$stmt = $this->db->prepare('
            DELETE FROM users 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
		
	}
	
	public function findUser($username){
		$stmt = $this->db->prepare('
            SELECT "User", users.* 
             FROM users 
             WHERE username = :username
        ');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		
        return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	public function getAllUsers(){
		$stmt = $this->db->prepare('
            SELECT * FROM users
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        
        return $stmt->fetchAll();
	}
}