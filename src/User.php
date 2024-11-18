<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'] ?? null;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }
    public function getEmail() {
        return $this->email;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }
}

