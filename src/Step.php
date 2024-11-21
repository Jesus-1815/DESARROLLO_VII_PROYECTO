<?php

class Step {
    public $id;
    public $recipeId;
    public $stepNumber;
    public $description;
    public $createdAt;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->recipeId = $data['recipe_id'];
        $this->stepNumber = $data['step_number'];
        $this->description = $data['description'];
        $this->createdAt = $data['created_at'] ?? null;
    }
}
?>

