<?php
require_once 'RecipeIngredient.php';

class RecipeIngredientManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getIngredientsByRecipe($recipeId) {
        $stmt = $this->db->prepare("SELECT ri.*, i.name 
            FROM recipe_ingredients ri 
            JOIN ingredients i ON ri.ingredient_id = i.id 
            WHERE ri.recipe_id = ?");
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecipesByIngredient($ingredientId) {
        $stmt = $this->db->prepare("SELECT ri.*, r.title 
            FROM recipe_ingredients ri 
            JOIN recipes r ON ri.recipe_id = r.id 
            WHERE ri.ingredient_id = ?");
        $stmt->execute([$ingredientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
