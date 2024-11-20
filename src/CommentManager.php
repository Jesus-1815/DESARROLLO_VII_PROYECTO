<?php
require_once 'Comment.php';

class CommentManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addComment($recipeId, $userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (recipe_id, user_id, comment) VALUES (?, ?, ?)");
        return $stmt->execute([$recipeId, $userId, $content]);
    }

    public function getCommentsByRecipe($recipeId) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        $comments = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comments[] = new Comment($row);
        }
        return $comments;
    }
}
