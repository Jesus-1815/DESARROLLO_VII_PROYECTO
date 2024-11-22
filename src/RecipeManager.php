<?php
require_once 'Database.php';
require_once 'src/Recipe.php';
require_once 'src/StepManager.php';

class RecipeManager {
    private $db;
    private $stepManager;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->stepManager = new StepManager();
    }

    // Crear o actualizar receta
    public function createRecipe($user_id, $title, $description, $prep_time, $steps, $recipe_id = null) {
        try {
            if ($recipe_id) {
                // Actualización de receta existente
                $stmt = $this->db->prepare("
                    UPDATE recipes 
                    SET user_id = ?, title = ?, description = ?, prep_time = ? 
                    WHERE id = ?
                ");
                $stmt->execute([$user_id, $title, $description, $prep_time, $recipe_id]);
            } else {
                // Creación de nueva receta
                $stmt = $this->db->prepare("
                    INSERT INTO recipes (user_id, title, description, prep_time) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$user_id, $title, $description, $prep_time]);
                $recipe_id = $this->db->lastInsertId();  // Obtener el ID de la receta recién insertada
            }

            // Eliminar pasos existentes si es una actualización
            if ($recipe_id) {
                $stmt = $this->db->prepare("DELETE FROM recipe_steps WHERE recipe_id = ?");
                $stmt->execute([$recipe_id]);
            }

            // Guardar los pasos
            foreach ($steps as $stepText) {
                $stmt = $this->db->prepare("INSERT INTO steps (step_text, recipe_id) VALUES (?, ?)");
                $stmt->execute([$stepText, $recipe_id]);
                $stepId = $this->db->lastInsertId();

                $stmt = $this->db->prepare("INSERT INTO recipe_steps (recipe_id, step_id) VALUES (?, ?)");
                $stmt->execute([$recipe_id, $stepId]);
            }

            return $recipe_id;
        } catch (Exception $e) {
            throw new Exception("Error creating recipe: " . $e->getMessage());
        }
    }

    // Agregar ingrediente a la receta
    public function addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit) {
        $stmt = $this->db->prepare("SELECT id FROM ingredients WHERE name = ?");
        $stmt->execute([$ingredientName]);
        $ingredientId = $stmt->fetchColumn();

        if (!$ingredientId) {
            $stmt = $this->db->prepare("INSERT INTO ingredients (name) VALUES (?)");
            $stmt->execute([$ingredientName]);
            $ingredientId = $this->db->lastInsertId();
        }

        $stmt = $this->db->prepare("
            INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$recipeId, $ingredientId, $quantity, $unit]);
    }

    // Obtener receta por ID
    public function getRecipeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($recipe) {
            $stmt = $this->db->prepare("
                SELECT s.step_text 
                FROM steps s 
                INNER JOIN recipe_steps rs ON s.id = rs.step_id 
                WHERE rs.recipe_id = ?
            ");
            $stmt->execute([$id]);
            $steps = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
            $recipe['steps'] = $steps; // Añadir los pasos a la receta
            return new Recipe($recipe);
        }
        return null;
    }

    // Obtener todas las recetas
    public function getAllRecipes() {
        $stmt = $this->db->query("SELECT * FROM recipes ORDER BY created_at DESC");
        $recipes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipes[] = new Recipe($row);
        }
        return $recipes;
    }

    // Actualizar receta
    public function updateRecipe($id, $title, $description, $prep_time, $steps) {
        try {
            $stmt = $this->db->prepare("
                UPDATE recipes 
                SET title = ?, description = ?, prep_time = ? 
                WHERE id = ?
            ");
            $stmt->execute([$title, $description, $prep_time, $id]);

            // Eliminar relaciones existentes en recipe_steps
            $stmt = $this->db->prepare("DELETE FROM recipe_steps WHERE recipe_id = ?");
            $stmt->execute([$id]);

            // Agregar los nuevos pasos
            foreach ($steps as $stepText) {
                $stmt = $this->db->prepare("SELECT id FROM steps WHERE step_text = ?");
                $stmt->execute([$stepText]);
                $step = $stmt->fetch();

                if (!$step) {
                    $stmt = $this->db->prepare("INSERT INTO steps (step_text) VALUES (?)");
                    $stmt->execute([$stepText]);
                    $stepId = $this->db->lastInsertId();
                } else {
                    $stepId = $step['id'];
                }

                $stmt = $this->db->prepare("INSERT INTO recipe_steps (recipe_id, step_id) VALUES (?, ?)");
                $stmt->execute([$id, $stepId]);
            }

            return true;
        } catch (Exception $e) {
            throw new Exception("Error updating recipe: " . $e->getMessage());
        }
    }

    // Obtener ingredientes por receta
    public function getIngredientsByRecipeId($recipe_id) {
        $stmt = $this->db->prepare("
            SELECT i.name, ri.quantity, ri.unit 
            FROM ingredients i
            JOIN recipe_ingredients ri ON i.id = ri.ingredient_id
            WHERE ri.recipe_id = ?
        ");
        $stmt->execute([$recipe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar receta
    public function deleteRecipe($id) {
        $this->stepManager->deleteStepsByRecipeId($id);
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

