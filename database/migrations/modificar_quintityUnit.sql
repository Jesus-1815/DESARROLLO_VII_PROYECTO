-- Renombrar la tabla original para respaldo
RENAME TABLE recipe_ingredients TO recipe_ingredients_old;

-- Crear la nueva tabla
CREATE TABLE recipe_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE
);

-- Copiar datos existentes, asumiendo que el campo quantity en la tabla original contenía "cantidad unidad"
INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit)
SELECT 
    recipe_id, 
    ingredient_id, 
    SUBSTRING_INDEX(quantity, ' ', 1),  -- Extrae la cantidad antes del primer espacio
    SUBSTRING_INDEX(quantity, ' ', -1) -- Extrae la unidad después del primer espacio
FROM recipe_ingredients_old;

-- Eliminar la tabla antigua
DROP TABLE recipe_ingredients_old;
