<?php
class Ingredient {
    private $id;
    private $name;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
