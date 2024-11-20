<?php
class Rating {
    private $id;
    private $recipeId;
    private $userId;
    private $rating;
    private $createdAt;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->recipeId = $data['recipe_id'];
        $this->userId = $data['user_id'];
        $this->rating = $data['rating'];
        $this->createdAt = $data['created_at'] ?? null;
    }

    public function getId() {
        return $this->id;
    }

    public function getRecipeId() {
        return $this->recipeId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}
