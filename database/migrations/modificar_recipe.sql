ALTER TABLE recipes DROP COLUMN steps;
ALTER TABLE steps ADD COLUMN step_order INT NOT NULL AFTER recipe_id;
