<?php
class Task {
    public $id;
    public $title;
    public $isCompleted;
    public $createdAt;

    // Constructor para crear un objeto Task a partir de un array de datos
    public function __construct($data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->isCompleted = $data['is_completed'];
        $this->createdAt = $data['created_at'];
    }

    // Aquí podrían añadirse métodos adicionales relacionados con una tarea individual
}