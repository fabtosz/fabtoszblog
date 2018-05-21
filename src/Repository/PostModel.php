<?php

namespace FabtoszBlog\Repository;

use FabtoszBlog\Repository\AbstractModel;
use FabtoszBlog\Entity\Post;
use PDO;

class PostModel extends AbstractModel {
	
	public function insertPost(Post $post){
		// If the ID is set, we're updating an existing record
        if (isset($post->id)) {
            return $this->updatePost($post);
        }
        $stmt = $this->db->prepare('
            INSERT INTO posts 
                (title, content, author, publishedAt, category_id) 
            VALUES 
                (:title, :content, :author, :publishedAt, :category)
        ');
        $stmt->bindParam(':title', $post->title);
        $stmt->bindParam(':content', $post->content);
        $stmt->bindParam(':author', $post->author);
        $stmt->bindParam(':publishedAt', $post->publishedAt);
		$stmt->bindParam(':category', $post->category);
		
        return $stmt->execute();
	}
	
	public function updatePost(Post $post){
		if (!isset($post->id)) {
            // We can't update a record unless it exists...
            throw new \Exception(
                'Cannot update post that does not yet exist in the database.'
            );
        }
        $stmt = $this->db->prepare('
            UPDATE posts
            SET title = :title,
                content = :content,
                author = :author,
                publishedAt = :publishedAt
				category = :category
            WHERE id = :id
        ');
        $stmt->bindParam(':title', $post->title);
        $stmt->bindParam(':content', $post->content);
        $stmt->bindParam(':author', $post->author);
        $stmt->bindParam(':publishedAt', $post->publishedAt);
		$stmt->bindParam(':category', $post->category);
        $stmt->bindParam(':id', $post->id);
        return $stmt->execute();
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
		$stmt = $this->db->prepare('
            SELECT "Post", posts.* 
             FROM posts 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
		
        return $stmt->fetch(PDO::FETCH_OBJ);
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
	
	//Próba stworzenia systemu paginacji za pomocą wydajnej Seek Method
	//Niestety działa tylko z unikalnymi rekordami, więc nie można stosować
	//gdy unikalne rekordy posiadają duplikaty i są luki między nimi
	/*
	public function getPostsByPage($page, $records_per_page, $first, $last) {
		
		$firstId = $this->getFirstPostId();
		$recordsCount = $this->getPostsCount();
		$pagesCount = ceil($recordsCount/$records_per_page);
		
		$offset = $firstId + ($page * $records_per_page);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		$stmt = $this->db->prepare('
            SELECT * FROM posts
			WHERE id < :offset
			LIMIT :records_per_page
        ');
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Post');
        
        return $stmt->fetchAll();
		
	}
	*/
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
        
        //return $stmt->fetchAll();
		
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