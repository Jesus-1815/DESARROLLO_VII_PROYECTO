<?php
require_once 'Ingredient.php';

class IngredientManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createIngredient($name) {
        $stmt = $this->db->prepare("INSERT INTO ingredients (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function getIngredientById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ingredients WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Ingredient($data) : null;
    }

    public function getAllIngredients() {
        $stmt = $this->db->query("SELECT * FROM ingredients");
        $ingredients = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredients[] = new Ingredient($row);
        }
        return $ingredients;
    }
}
