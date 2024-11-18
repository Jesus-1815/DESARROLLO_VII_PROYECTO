<?php
require_once 'Database.php';

class RecipeManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createRecipe($user_id, $title, $description, $prep_time, $steps) {
        $stmt = $this->db->prepare("
            INSERT INTO recipes (user_id, title, description, prep_time, steps) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([$user_id, $title, $description, $prep_time, $steps]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating recipe: " . $e->getMessage());
        }
    }
    public function save(Recipe $recipe) {
        $stmt = $this->db->prepare("INSERT INTO recipes (user_id, title, description, prep_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $recipe->getUserId(),
            $recipe->getTitle(),
            $recipe->getDescription(),
            $recipe->getPrepTime()
        ]);
        return $this->db->lastInsertId();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM recipes");
        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipes[] = new Recipe($row);
        }
        return $recipes;
    }

    public function getRecipeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        return $recipe ? new Recipe($recipe) : null;
    }

    public function updateRecipe($id, $title, $description, $prep_time, $steps) {
        $stmt = $this->db->prepare("
            UPDATE recipes 
            SET title = ?, description = ?, prep_time = ?, steps = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$title, $description, $prep_time, $steps, $id]);
    }

    public function deleteRecipe($id) {
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllRecipes() {
        $stmt = $this->db->query("SELECT * FROM recipes ORDER BY created_at DESC");
        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipes[] = new Recipe($row);
        }
        return $recipes;
    }
}
