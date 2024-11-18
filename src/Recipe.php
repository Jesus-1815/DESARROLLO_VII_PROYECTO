<?php

class Recipe {
    private $id;
    private $user_id;
    private $title;
    private $description;
    private $prep_time;
    private $steps;
    private $created_at;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'];
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->prep_time = $data['prep_time'] ?? null;
        $this->steps = $data['steps'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }
    public function getTitle(): string {
        return $this->title;
    }

    // Getters
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

    public function getCreatedAt() {
        return $this->created_at;
    }
}





