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
    public function createRecipe($userId, $title, $description, $prepTime, $ingredients, $steps) {
        try {
            $horas = floor($prepTime / 60);
            $minutosRestantes = $prepTime % 60;
            
            $prepTime= sprintf('%02d:%02d:00', $horas, $minutosRestantes);
            $query = "INSERT INTO recipes (user_id, title, description, prep_time) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId, $title, $description, $prepTime]);
    
            // Obtener el ID de la receta recién creada
            $recipeId = $this->db->lastInsertId();
    
            // Aquí puedes insertar ingredientes y pasos si es necesario
            return $recipeId; // ¡Devuelve el ID de la receta!
        } catch (Exception $e) {
            throw new Exception("Error creating recipe: " . $e->getMessage());
        }
    }
    
    
    
    // Agregar ingrediente a la receta
    public function addIngredientToRecipe($recipeId, $ingredientName, $quantity, $unit = null) {
        try {
            // Inserta el ingrediente en la receta con la unidad de medida
            $stmt = $this->db->prepare("
                INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
                SELECT :recipe_id, id, :quantity, :unit
                FROM ingredients WHERE name = :ingredient_name
            ");
            $stmt->bindParam(':recipe_id', $recipeId);  // Asegúrate de pasar el `recipe_id`
            $stmt->bindParam(':ingredient_name', $ingredientName);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':unit', $unit);
    
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error adding ingredient to recipe: " . $e->getMessage());
        }
    }
        
    // Obtener receta por ID
    public function getRecipeById($id) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = ?");
        $stmt->execute([$id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($recipe) {
            $stmt = $this->db->prepare("SELECT s.step_text 
                FROM steps s 
                INNER JOIN recipe_steps rs ON s.id = rs.step_id 
                WHERE rs.recipe_id = ?");
            $stmt->execute([$id]);
            $steps = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
            $recipe['steps'] = $steps;
            return new Recipe($recipe);
        }

        return null; // Si no se encuentra la receta, devuelve null

    } catch (Exception $e) {
        // Maneja el error y lanza una excepción o loguea el error
        throw new Exception("Error al obtener la receta: " . $e->getMessage());
    }
}
// Obtener todas las recetas o buscar recetas si se proporciona un término de búsqueda
public function getAllRecipes($searchQuery = '') {
    // Si hay un término de búsqueda, realizar la búsqueda
    if ($searchQuery) {
        // Preparamos la consulta SQL con los filtros por título, ingredientes y tiempo de preparación
        $sql = "SELECT DISTINCT r.* 
                FROM recipes r
                LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
                LEFT JOIN ingredients i ON ri.ingredient_id = i.id
                WHERE r.title LIKE :query
                OR r.prep_time LIKE :query
                OR i.name LIKE :query
                ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        
        // Ejecutamos la consulta con el parámetro de búsqueda
        $stmt->execute([':query' => "%$searchQuery%"]);
    } else {
        // Si no hay término de búsqueda, obtener todas las recetas
        $stmt = $this->db->query("SELECT * FROM recipes ORDER BY created_at DESC");
    }

    $recipes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Creamos un objeto de tipo Recipe y asignamos los datos obtenidos
        $recipe = new Recipe($row);
        
        // Asignamos la ruta de la imagen si está disponible en la base de datos
        if (isset($row['image_path']) && !empty($row['image_path'])) {
            $recipe->setImagePath($row['image_path']);  // Asignamos la imagen a la receta
        }
        
        // Añadimos la receta al array de recetas
        $recipes[] = $recipe;
    }
    
    return $recipes;
}

    // Actualizar receta
    public function updateRecipe($recipeId, $title, $description, $prepTime, $ingredients, $steps) {
        try {
            // Actualiza la información básica de la receta
            $stmt = $this->db->prepare("
                UPDATE recipes 
                SET title = ?, description = ?, prep_time = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $description, $prepTime, $recipeId]);
    
            // Elimina ingredientes antiguos
            $stmt = $this->db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
            $stmt->execute([$recipeId]);
    
            // Inserta los nuevos ingredientes
            foreach ($ingredients as $ingredient) {
                $stmt = $this->db->prepare("
                    INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
                    SELECT ?, id, ?, ?
                    FROM ingredients WHERE name = ?
                ");
                $stmt->execute([$recipeId, $ingredient['quantity'], $ingredient['unit'], $ingredient['name']]);
            }
    
            // Elimina los pasos antiguos
            $stmt = $this->db->prepare("DELETE FROM recipe_steps WHERE recipe_id = ?");
            $stmt->execute([$recipeId]);
    
            // Inserta los nuevos pasos
            foreach ($steps as $index => $step) {
                $stmt = $this->db->prepare("
                    INSERT INTO recipe_steps (recipe_id, step_order, step_text) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$recipeId, $index + 1, $step]);
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
    

    public function addImageToRecipe($recipeId, $imagePath) {
        try {
            $stmt = $this->db->prepare("INSERT INTO recipe_images (recipe_id, image_path) VALUES (?, ?)");
            $stmt->execute([$recipeId, $imagePath]);
        } catch (Exception $e) {
            throw new Exception("Error al guardar la imagen: " . $e->getMessage());
        }
    }
    
    public function getImagesByRecipeId($recipeId) {
    $stmt = $this->db->prepare("SELECT image_path FROM recipe_images WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


    // Eliminar receta
    public function deleteRecipe($id) {
        $this->stepManager->deleteStepsByRecipeId($id);
        $stmt = $this->db->prepare("DELETE FROM recipes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function clearRecipeIngredients($recipeId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
            $stmt->execute([$recipeId]);
        } catch (Exception $e) {
            throw new Exception("Error clearing recipe ingredients: " . $e->getMessage());
        }
    }
    
}
?>


