<?php

namespace FabtoszBlog\Repository;

use FabtoszBlog\Repository\AbstractModel;
use FabtoszBlog\Entity\Post;
use PDO;

class PostModel extends AbstractModel {
	
	public function insertPost(Post $post){
		
        if (isset($post->id)) {
            return $this->updatePost($post);
        }
        $stmt = $this->db->prepare('
            INSERT INTO posts 
                (title, content, author, publishedAt, categoryId) 
            VALUES 
                (:title, :content, :author, :publishedAt, :category)
        ');

		$title = $post->getTitle();
		$contet = $post->getContent();			
		$author = $post->getAuthor();
		$publishedAt = date("Y-m-d H:i:s");
		$categoryId = $post->getCategoryId();
			
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $contet);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':publishedAt', $publishedAt);
		$stmt->bindParam(':category', $categoryId);
		
        return $stmt->execute();
	}
	
	public function updatePost(Post $post){
		
		try {
			$sql = "UPDATE posts 
				SET title = :title, 
					content = :content, 
					author = :author,  
					publishedAt = :publishedAt,  
					categoryId = :categoryId  
				WHERE id = :id";
				
			$id = $post->getId();
			$title = $post->getTitle();
			$contet = $post->getContent();
			$author = $post->getAuthor();
			$publishedAt = date("Y-m-d H:i:s");
			$categoryId = $post->getCategoryId();
			
			$stmt = $this->db->prepare($sql);                                  
			$stmt->bindParam(':title', $title, PDO::PARAM_STR);       
			$stmt->bindParam(':content', $contet, PDO::PARAM_STR);    
			$stmt->bindParam(':author', $author, PDO::PARAM_STR);
			$stmt->bindParam(':publishedAt', $publishedAt, PDO::PARAM_STR); 
			$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);   
			$stmt->bindParam(':id', $id, PDO::PARAM_INT); 
			
			return $stmt->execute(); 
		} catch(PDOException $e) {
			echo $e->getMessage();
		}

	}
	
	public function deletePost($id){
		$stmt = $this->db->prepare('
            DELETE FROM posts 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
		
	}
	
	public function getPost($id){
		
		$stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'FabtoszBlog\Entity\Post'); //zeby nie wywolywal sie domyslny kontruktor
		$stmt->execute();
		$post = $stmt->fetch();
		
		return $post;
	}
	
	public function getAllPosts(){
		$stmt = $this->db->prepare('
            SELECT * FROM posts
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        
        return $stmt->fetchAll();
	}
	
	public function getNLastPosts($count){
		
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		
		$stmt = $this->db->prepare('
            SELECT * FROM posts
			ORDER BY publishedAt DESC 
			LIMIT :count
        ');
		$stmt->bindParam(':count', $count, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        
        return $stmt->fetchAll();
	}
	
	public function getPostsByPage($page, $records_per_page) {
		
		$totalRecords = $this->getPostsCount();
		$pagesCount = ceil($totalRecords/$records_per_page);
		
		$offset = ($page - 1) * $records_per_page;
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		$stmt = $this->db->prepare('
            SELECT * FROM posts
			LIMIT :offset, :records_per_page
        ');
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
		
		return [
			'records' => $stmt->fetchAll(),
			'pages_count' => $pagesCount,
			'total_records' => $totalRecords
		];
		
	}
	
	private function getPostsCount() {
		$stmt = $this->db->prepare("SELECT count(*) FROM posts");
		$stmt->execute();
		$count = $stmt->fetchColumn();
		
		return $count;
	}
	
	private function getFirstPostId() {
		$stmt = $this->db->prepare("SELECT id FROM posts LIMIT 1");
		$stmt->execute();
		$firstId = $stmt->fetchColumn();
		
		return $firstId;
	}
}