<?php

class Recipe {
    private $id;
    private $user_id;
    public $name;
    private $title;
    private $description;
    private $prep_time;
    private $steps;
    public $ingredients = [];
    private $created_at;
    private $imagePath;

    public function __construct(array $data) {
        $this->name = $data['name'] ?? null; // Asigna 'name' desde $data
        $this->ingredients = $data['ingredients'] ?? []; // Asigna 'ingredients' desde $data

        // Asigna las demás propiedades con los datos proporcionados en $data
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->prep_time = $data['prep_time'] ?? null;
        $this->steps = $data['steps'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->imagePath = isset($data['image_path']) ? $data['image_path'] : null;
    }

    // Métodos Getter
    public function getTitle(): string {
        return $this->title;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrepTime() {
        return $this->prep_time;
    }

    public function getSteps() {
        return $this->steps;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
    
    public function getImagePath() {
        return $this->imagePath;
    }

    public function setImagePath($imagePath) {
        $this->imagePath = $imagePath;
    }
    public function getAverageRating() {  
        // Aquí deberías calcular la calificación promedio basada en los comentarios/ranking  
        // Este es un ejemplo simple que asume que tienes acceso a un array de ratings  
        $ratings = $this->getRatings(); // Método hipotético que debe retornar un array de calificaciones  
        if (empty($ratings)) return 0; // Si no hay calificaciones, retorna 0  

        $total = array_sum($ratings); // Suma todos los ratings  
        $count = count($ratings); // Cuenta cuántos ratings hay  
        return round($total / $count, 1); // Retorna la calificación promedio redondeada  
    }  

    // Método que obtiene las calificaciones de la receta (puedes implementar esto según tu base de datos)  
    public function getRatings() {  
        // Lógica para obtener las calificaciones de la base de datos  
        // Retornar un array de calificaciones como [4, 5, 3]  
    } 
}
?>






