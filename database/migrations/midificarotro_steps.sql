ALTER TABLE steps
    ADD CONSTRAINT fk_recipe_id
        FOREIGN KEY (recipe_id)
        REFERENCES recipes(id)
        ON DELETE CASCADE;
