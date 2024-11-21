<?php

require_once 'Database.php';
require_once 'Step.php';

class StepManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createStep($recipeId, $stepNumber, $description) {
        $stmt = $this->db->prepare("INSERT INTO steps (recipe_id, step_number, description) VALUES (?, ?, ?)");
        return $stmt->execute([$recipeId, $stepNumber, $description]);
    }

    public function getStepsByRecipeId($recipeId) {
        $stmt = $this->db->prepare("SELECT * FROM steps WHERE recipe_id = ? ORDER BY step_number ASC");
        $stmt->execute([$recipeId]);
        $steps = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $steps[] = new Step($row);
        }
        return $steps;
    }

    public function updateStep($id, $stepNumber, $description) {
        $stmt = $this->db->prepare("UPDATE steps SET step_number = ?, description = ? WHERE id = ?");
        return $stmt->execute([$stepNumber, $description, $id]);
    }

    public function deleteStep($id) {
        $stmt = $this->db->prepare("DELETE FROM steps WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteStepsByRecipeId($recipeId) {
        $stmt = $this->db->prepare("DELETE FROM steps WHERE recipe_id = ?");
        return $stmt->execute([$recipeId]);
    }
}
?>
