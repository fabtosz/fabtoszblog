<?php

namespace FabtoszBlog\Repository;

use FabtoszBlog\Repository\AbstractModel;
use FabtoszBlog\Entity\Comment;
use PDO;

class CommentModel extends AbstractModel {
	
	public function insertComment(Comment $comment){
		// If the ID is set, we're updating an existing record
        if (isset($comment->id)) {
            return $this->updateComment($comment);
        }
        $stmt = $this->db->prepare('
            INSERT INTO comments 
                (comment, author, publishedAt, post_id) 
            VALUES 
                (:comment, :author, :publishedAt, :post_id)
        ');
        $stmt->bindParam(':comment', $comment->comment);
        $stmt->bindParam(':author', $comment->author);
        $stmt->bindParam(':publishedAt', $comment->publishedAt);
		$stmt->bindParam(':post_id', $comment->postId);
		
        return $stmt->execute();
	}
	
	public function getAllCommentsByPostId($postId){
		$stmt = $this->db->prepare('
            SELECT * FROM comments
			WHERE postId = :postId
        ');
		$stmt->bindParam(':postId', $postId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        
        return $stmt->fetchAll();
	}
	
	public function getAllComments(){
		$stmt = $this->db->prepare('
            SELECT * FROM comments
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
        
        return $stmt->fetchAll();
	}
	
	public function getCommentsByPage($page, $records_per_page) {
		
		$totalRecords = $this->getCommentsCount();
		$pagesCount = ceil($totalRecords/$records_per_page);
		
		$offset = ($page - 1) * $records_per_page;
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
		$stmt = $this->db->prepare('
            SELECT * FROM comments
			LIMIT :offset, :records_per_page
        ');
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Comment');
		
		return [
			'records' => $stmt->fetchAll(),
			'pages_count' => $pagesCount,
			'total_records' => $totalRecords
		];
		
	}
	
	private function getCommentsCount() {
		$stmt = $this->db->prepare("SELECT count(*) FROM comments");
		$stmt->execute();
		$count = $stmt->fetchColumn();
		
		return $count;
	}
}