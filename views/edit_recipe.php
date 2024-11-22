<?php
// Iniciamos el buffer de salida
ob_start();
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receta: <?php echo $recipe->name; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
</head>
<body>
    <h1>Editar Receta: <?php echo $recipe->name; ?></h1>
    <form action="<?php echo BASE_URL; ?>/index.php?action=update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="recipe_id" value="<?php echo $recipe->id; ?>">

        <label for="recipe-name">Nombre de la receta:</label>
        <input type="text" id="recipe-name" name="recipe_name" value="<?php echo $recipe->name; ?>" maxlength="100" required>

        <label for="prep-time">Tiempo de preparación (en minutos):</label>
        <input type="number" id="prep-time" name="prep_time" min="1" max="500" value="<?php echo $recipe->prep_time; ?>" required>

        <label>Ingredientes:</label>
        <div id="ingredients-container">
            <?php foreach ($recipe->ingredients as $ingredient): ?>
                <div class="ingredient-row">
                    <input type="number" name="quantity[]" value="<?php echo $ingredient['quantity']; ?>" placeholder="Cantidad" min="0" step="0.1" required>
                    <select name="unit[]" required>
                        <!-- Opciones de unidades como antes -->
                    </select>
                    <input type="text" name="ingredient[]" value="<?php echo $ingredient['name']; ?>" placeholder="Ingrediente" maxlength="50" required>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <button type="button" id="add-ingredient">Más</button>
            <button type="button" id="remove-ingredient" class="remove">Menos</button>
        </div>

        <label for="steps">Pasos a seguir:</label>
        <div id="steps-container">
            <?php foreach ($recipe->steps as $step): ?>
                <div class="step-row">
                    <textarea name="steps[]" rows="3" placeholder="Describe un paso" required><?php echo $step; ?></textarea>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="actions">
            <button type="button" id="add-step">Más</button>
            <button type="button" id="remove-step" class="remove">Menos</button>
        </div>

        <label for="description">Descripción:</label>
        <textarea id="description" name="description" rows="3" placeholder="Ej. Receta especial para fiestas" required><?php echo $recipe->description; ?></textarea>

        <button type="submit" id="submit-button">Guardar Receta</button>
    </form>

    <script>
        const ingredientsContainer = document.getElementById('ingredients-container');
        const addIngredientButton = document.getElementById('add-ingredient');
        const removeIngredientButton = document.getElementById('remove-ingredient');
        const stepsContainer = document.getElementById('steps-container');
        const addStepButton = document.getElementById('add-step');
        const removeStepButton = document.getElementById('remove-step');

        // Agregar una fila de ingredientes
        addIngredientButton.addEventListener('click', () => {
            const ingredientRow = document.createElement('div');
            ingredientRow.classList.add('ingredient-row');

            ingredientRow.innerHTML = `
                <input type="number" name="quantity[]" placeholder="Cantidad" min="0" step="0.1" required>
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
                    <option value="u">Unidades</option>
                </select>
                <input type="text" name="ingredient[]" placeholder="Ingrediente" maxlength="50" required>
            `;

            ingredientsContainer.appendChild(ingredientRow);
        });

        // Eliminar la última fila de ingredientes
        removeIngredientButton.addEventListener('click', () => {
            const rows = ingredientsContainer.getElementsByClassName('ingredient-row');
            if (rows.length > 1) {
                ingredientsContainer.removeChild(rows[rows.length - 1]);
            }
        });

        // Agregar una fila de pasos
        addStepButton.addEventListener('click', () => {
            const stepRow = document.createElement('div');
            stepRow.classList.add('step-row');

            stepRow.innerHTML = `
                <textarea name="steps[]" rows="3" placeholder="Describe un paso" required></textarea>
            `;

            stepsContainer.appendChild(stepRow);
        });

        // Eliminar la última fila de pasos
        removeStepButton.addEventListener('click', () => {
            const rows = stepsContainer.getElementsByClassName('step-row');
            if (rows.length > 1) {
                stepsContainer.removeChild(rows[rows.length - 1]);
            }
        });
    </script>
</body>
</html>

<?php
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require 'layout.php';
?>

