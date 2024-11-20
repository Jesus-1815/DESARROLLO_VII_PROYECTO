<?php
class StepManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Método para agregar un paso a una receta
    public function addStep($recipeId, $description) {
        $query = "INSERT INTO steps (recipe_id, step_order, description) 
                  SELECT :recipe_id, COALESCE(MAX(step_order), 0) + 1, :description 
                  FROM steps 
                  WHERE recipe_id = :recipe_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Método para obtener todos los pasos de una receta
    public function getStepsByRecipe($recipeId) {
        $query = "SELECT step_order, description 
                  FROM steps 
                  WHERE recipe_id = :recipe_id 
                  ORDER BY step_order ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

