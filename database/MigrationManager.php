<?php

class MigrationManager {
    private $pdo;
    private $migrationsPath;

    public function __construct($pdo, $migrationsPath) {
        $this->pdo = $pdo;
        $this->migrationsPath = $migrationsPath;
    }

    public function migrate() {
        $this->createMigrationsTableIfNotExists();
        $appliedMigrations = $this->getAppliedMigrations();
        $files = $this->getMigrationFiles();
        $newMigrations = array_diff($files, $appliedMigrations);

        foreach ($newMigrations as $migration) {
            $this->applyMigration($migration);
        }

        if (empty($newMigrations)) {
            echo "No new migrations to apply.\n";
        }
    }

    private function createMigrationsTableIfNotExists() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    private function getAppliedMigrations() {
        $statement = $this->pdo->query("SELECT migration FROM migrations");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getMigrationFiles() {
        $files = scandir($this->migrationsPath);
        $migrations = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
        });
        sort($migrations);
        return $migrations;
    }

    private function applyMigration($migration) {
        $sql = file_get_contents($this->migrationsPath . '/' . $migration);
        $this->pdo->exec($sql);
        $this->pdo->exec("INSERT INTO migrations (migration) VALUES ('$migration')");
        echo "Applied migration: $migration\n";
    }
}