CREATE TABLE recipe_steps (
    recipe_id INT NOT NULL,
    step_id INT NOT NULL,
    PRIMARY KEY (recipe_id, step_id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (step_id) REFERENCES steps(id) ON DELETE CASCADE
);