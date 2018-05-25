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
}