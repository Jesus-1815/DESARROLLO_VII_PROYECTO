<?php
require_once 'Database.php';
require_once 'Step.php';

class StepManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Crear paso
    public function createStep($recipeId, $stepNumber, $stepText) {
        $stmt = $this->db->prepare("
            INSERT INTO steps (recipe_id, step_text, step_number) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$recipeId, $stepText, $stepNumber]);
    }

    // Obtener pasos por receta
    public function getStepsByRecipeId($recipeId) {
        $stmt = $this->db->prepare("
            SELECT id, step_text, step_number 
            FROM steps 
            WHERE recipe_id = ? 
            ORDER BY step_number ASC
        ");
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar paso
    public function updateStep($id, $stepNumber, $stepText) {
        $stmt = $this->db->prepare("
            UPDATE steps 
            SET step_number = ?, step_text = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$stepNumber, $stepText, $id]);
    }

    // Borrar paso por ID
    public function deleteStep($id) {
        $stmt = $this->db->prepare("DELETE FROM steps WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Borrar todos los pasos de una receta
    public function deleteStepsByRecipeId($recipeId) {
        $stmt = $this->db->prepare("DELETE FROM steps WHERE recipe_id = ?");
        return $stmt->execute([$recipeId]);
    }
}

?>
