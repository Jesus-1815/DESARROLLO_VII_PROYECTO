<?php
class TaskManager {
    private $db;

    public function __construct() {
        // Obtenemos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para obtener todas las tareas
    public function getAllTasks() {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para crear una nueva tarea
    public function createTask($title) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title) VALUES (?)");
        return $stmt->execute([$title]);
    }

    // Método para cambiar el estado de una tarea (completada/no completada)
    public function toggleTask($id) {
        $stmt = $this->db->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Método para eliminar una tarea
    public function deleteTask($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}