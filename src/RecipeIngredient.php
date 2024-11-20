<?php
class RecipeIngredient {
    private $recipeId;
    private $ingredientId;
    private $quantity;

    public function __construct($data) {
        $this->recipeId = $data['recipe_id'];
        $this->ingredientId = $data['ingredient_id'];
        $this->quantity = $data['quantity'] ?? null;
    }

    public function getRecipeId() {
        return $this->recipeId;
    }

    public function getIngredientId() {
        return $this->ingredientId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}
