<?php
// Verifica que la receta exista y se haya cargado correctamente
if (!isset($recipe)) {
    echo "Receta no encontrada.";
    exit;
}

// Obtén los ingredientes asociados a la receta
$ingredients = $recipeManager->getIngredientsByRecipeId($recipe->getId());
$steps = $recipe->getSteps();
?>

<h2>Editar Receta: <?php echo htmlspecialchars($recipe->getTitle()); ?></h2>

<form action="index.php?action=update" method="POST" enctype="multipart/form-data" class="recipe-form">
    <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe->getId()); ?>">

    <!-- Nombre de la receta -->
    <div class="form-group">
        <label for="recipe_name">Nombre de la receta:</label>
        <input type="text" id="recipe_name" name="recipe_name" value="<?php echo htmlspecialchars($recipe->getTitle()); ?>" required>
    </div>

    <!-- Descripción de la receta -->
    <div class="form-group">
        <label for="description">Descripción:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($recipe->getDescription()); ?></textarea>
    </div>

    <!-- Tiempo de preparación -->
    <div class="form-group">
        <label for="prep_time">Tiempo de preparación:</label>
        <input type="text" id="prep_time" name="prep_time" value="<?php echo htmlspecialchars($recipe->getPrepTime()); ?>" required>
    </div>

    <!-- Ingredientes -->
    <h3>Ingredientes:</h3>
    <div id="ingredients-container">
        <?php foreach ($ingredients as $index => $ingredient): ?>
            <div class="ingredient-item">
                <input type="text" name="ingredient[]" value="<?php echo htmlspecialchars($ingredient['name']); ?>" placeholder="Ingrediente" required>
                <input type="number" name="quantity[]" value="<?php echo htmlspecialchars($ingredient['quantity']); ?>" placeholder="Cantidad" required>
                <select name="unit[]" required>
                    <option value="tazas">Tazas</option>
                    <option value="oz">Onzas</option>
                    <option value="lb">Libras</option>
                    <option value="ml">Mililitros</option>
                    <option value="l">Litros</option>
                    <option value="gr">Gramos</option>
                    <option value="kg">Kilogramos</option>
                    <option value="Cda">Cucharadas</option>
                    <option value="cdta">Cucharaditas</option>
                </select>
                <button type="button" class="remove-ingredient">Eliminar</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-ingredient">Agregar ingrediente</button>

    <!-- Pasos -->
    <h3>Pasos:</h3>
    <div id="steps-container">
        <?php foreach ($steps as $step): ?>
            <div class="step-item">
                <textarea name="steps[]" required><?php echo htmlspecialchars($step); ?></textarea>
                <button type="button" class="remove-step">Eliminar</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-step">Agregar paso</button>

    <!-- Imágenes -->
    <h3>Imágenes (opcional):</h3>
    <input type="file" name="recipe_images[]" multiple><br>

    <!-- Botón de envío -->
    <button type="submit" class="submit-btn">Actualizar receta</button>
</form>

<script>
// Agregar ingredientes dinámicamente
document.getElementById('add-ingredient').addEventListener('click', function() {
    const container = document.getElementById('ingredients-container');
    const newIngredient = document.createElement('div');
    newIngredient.classList.add('ingredient-item');
    newIngredient.innerHTML = `
        <input type="text" name="ingredient[]" placeholder="Ingrediente" required>
        <input type="number" name="quantity[]" placeholder="Cantidad" required>
        <select name="unit[]" required>
            <option value="tazas">Tazas</option>
            <option value="oz">Onzas</option>
            <option value="lb">Libras</option>
            <option value="ml">Mililitros</option>
            <option value="l">Litros</option>
            <option value="gr">Gramos</option>
            <option value="kg">Kilogramos</option>
            <option value="Cda">Cucharadas</option>
            <option value="cdta">Cucharaditas</option>
        </select>
        <button type="button" class="remove-ingredient">Eliminar</button>
    `;
    container.appendChild(newIngredient);
});

// Eliminar ingredientes
document.getElementById('ingredients-container').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-ingredient')) {
        event.target.closest('.ingredient-item').remove();
    }
});

// Agregar pasos dinámicamente
document.getElementById('add-step').addEventListener('click', function() {
    const container = document.getElementById('steps-container');
    const newStep = document.createElement('div');
    newStep.classList.add('step-item');
    newStep.innerHTML = `
        <textarea name="steps[]" required></textarea>
        <button type="button" class="remove-step">Eliminar</button>
    `;
    container.appendChild(newStep);
});

// Eliminar pasos
document.getElementById('steps-container').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-step')) {
        event.target.closest('.step-item').remove();
    }
});
</script>

<!-- Agregar un CSS interno para mejorar el diseño -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    .recipe-form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .ingredient-item, .step-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .ingredient-item input, .step-item textarea {
        margin-right: 10px;
    }

    .ingredient-item button, .step-item button {
        margin-left: 10px;
        background-color: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    .ingredient-item button:hover, .step-item button:hover {
        background-color: #d32f2f;
    }

    #add-ingredient, #add-step {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 4px;
    }

    #add-ingredient:hover, #add-step:hover {
        background-color: #45a049;
    }

    .submit-btn {
        background-color: #2196F3;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .submit-btn:hover {
        background-color: #0b7dda;
    }
</style>
