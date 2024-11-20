<?php
require_once 'Rating.php';

class RatingManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addRating($recipeId, $userId, $rating) {
        $stmt = $this->db->prepare("INSERT INTO ratings (recipe_id, user_id, rating) VALUES (?, ?, ?)");
        return $stmt->execute([$recipeId, $userId, $rating]);
    }

    public function getAverageRatingByRecipe($recipeId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as average_rating FROM ratings WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
    }
}
