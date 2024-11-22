<h1>Editar Receta</h1>
<form action="index.php?action=update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $recipe['id']; ?>">
    
    <label for="recipe-name">Nombre de la receta:</label>
    <input type="text" id="recipe-name" name="recipe_name" value="<?php echo htmlspecialchars($recipe['name']); ?>" required>

    <label for="prep-time">Tiempo de preparación (en minutos):</label>
    <input type="number" id="prep-time" name="prep_time" value="<?php echo $recipe['prep_time']; ?>" min="1" required>

    <label>Ingredientes:</label>
    <div id="ingredients-container">
        <?php foreach ($ingredients as $ingredient): ?>
            <div class="ingredient-row">
                <input type="number" name="quantity[]" value="<?php echo $ingredient['quantity']; ?>" required>
                <select name="unit[]" required>
                    <option value="tazas" <?php echo $ingredient['unit'] === 'tazas' ? 'selected' : ''; ?>>Tazas</option>
                    <option value="oz" <?php echo $ingredient['unit'] === 'oz' ? 'selected' : ''; ?>>Onzas</option>
                    <!-- Agrega las demás opciones -->
                </select>
                <input type="text" name="ingredient[]" value="<?php echo htmlspecialchars($ingredient['ingredient']); ?>" required>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-ingredient">Más</button>
    
    <label for="steps">Pasos a seguir:</label>
    <div id="steps-container">
        <?php foreach ($steps as $step): ?>
            <div class="step-item">
                <input type="text" name="steps[]" value="<?php echo htmlspecialchars($step['description']); ?>" required>
                <button type="button" class="remove-step">-</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-step">+</button>

    <button type="submit">Guardar Cambios</button>
</form>
