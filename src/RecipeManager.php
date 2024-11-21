<?php
require_once 'Database.php';
require_once 'src/Recipe.php';
require_once 'src/StepManager.php'; // Importar el StepManager

class RecipeManager {
    private $db;
    private $stepManager;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->stepManager = new StepManager(); // Instanciamos StepManager
    }

    public function createRecipe($user_id, $title, $description, $prep_time, $steps) {
        $stmt = $this->db->prepare("
            INSERT INTO recipes (user_id, title, description, prep_time) 
            VALUES (?, ?, ?, ?)
        ");
        
        try {
            $stmt->execute([$user_id, $title, $description, $prep_time]);
            $recipeId = $this->db->lastInsertId();
            
            // Insertar los pasos en la tabla steps
            foreach ($steps as $stepNumber => $stepDescription) {
                $this->stepManager->createStep($recipeId, $stepNumber + 1, $stepDescription);
            }
            
            return $recipeId;
        } catch (PDOException $e) {
            throw new Exception("Error creating recipe: " . $e->getMessage());
        }
    }

    public function addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit) {
        // Verifica si el ingrediente ya existe
        $stmt = $this->db->prepare("SELECT id FROM ingredients WHERE name = :name");
        $stmt->execute(['name' => $ingredientName]);
        $ingredientId = $stmt->fetchColumn();

        // Si no existe, lo crea
        if (!$ingredientId) {
            $stmt = $this->db->prepare("INSERT INTO ingredients (name) VALUES (:name)");
            $stmt->execute(['name' => $ingredientName]);
            $ingredientId = $stmt->lastInsertId();
        }

        // Agrega el ingrediente a la receta
        $stmt = $this->db->prepare("
            INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
            VALUES (:recipe_id, :ingredient_id, :quantity, :unit)
        ");
        $stmt->execute([
            'recipe_id' => $recipeId,
            'ingredient_id' => $ingredientId,
            'quantity' => $quantity,
            'unit' => $unit
        ]);
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
        if ($recipe) {
            $steps = $this->stepManager->getStepsByRecipeId($id);
            $recipe['steps'] = $steps;  // Añadir los pasos a la receta
            return new Recipe($recipe);
        }
        return null;
    }

    public function updateRecipe($id, $title, $description, $prep_time, $steps) {
        $stmt = $this->db->prepare("
            UPDATE recipes 
            SET title = ?, description = ?, prep_time = ? 
            WHERE id = ?
        ");
        $stmt->execute([$title, $description, $prep_time, $id]);

        // Actualizar los pasos (eliminarlos primero y luego insertar los nuevos)
        $this->stepManager->deleteStepsByRecipeId($id);
        foreach ($steps as $stepNumber => $stepDescription) {
            $this->stepManager->createStep($id, $stepNumber + 1, $stepDescription);
        }
        
        return true;
    }

    public function deleteRecipe($id) {
        // Eliminar los pasos primero
        $this->stepManager->deleteStepsByRecipeId($id);
        
        // Ahora eliminar la receta
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
?>

