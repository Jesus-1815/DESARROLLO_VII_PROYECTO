<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Receta</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">

    
</head>
<body>
    <h1>Agregar Receta</h1>
    <form action="RecipeManager.php" method="POST" enctype="multipart/form-data">
        <label for="recipe-name">Nombre de la receta:</label>
        <input type="text" id="recipe-name" name="recipe_name" required>

        <label for="prep-time">Tiempo de preparación (en minutos):</label>
        <input type="number" id="prep-time" name="prep_time" min="1" required>

        <label>Ingredientes:</label>
        <div id="ingredients-container">
            <!-- Fila de ejemplo -->
            <div class="ingredient-row">
                <input type="number" name="quantity[]" placeholder="Cantidad" min="0" required>
                <select name="unit[]" required>
                    <option value="tazas">Tazas</option>
                    <option value="oz">Onzas</option>
                    <option value="lb">Libras</option>
                    <option value="ml">Mililitros</option>
                    <option value="l">Litros</option>
                    <option value="gr">Gramos</option>
                    <option value="kg">Kilogramos</option>
                    <option value="Cda">Cucharada</option>
                    <option value="cdta">Cucharadita</option>
                    <option value="u">Unidades</option>
                </select>
                <input type="text" name="ingredient[]" placeholder="Ingrediente" required>
            </div>
        </div>
        <div class="actions">
            <button type="button" id="add-ingredient">Más</button>
            <button type="button" id="remove-ingredient" class="remove">Menos</button>
        </div>

        <label for="photos">Fotos:</label>
        <input type="file" id="photos" name="photos[]" accept="image/*" multiple>

        <label for="description">Descripción:</label>
        <textarea id="description" name="description" rows="3" required></textarea>

        <label for="steps">Pasos a seguir:</label>
        <textarea id="steps" name="steps" rows="5" required></textarea>

        <button type="submit" id="submit-button">Guardar Receta</button>
    </form>

    <script>
        const ingredientsContainer = document.getElementById('ingredients-container');
        const addIngredientButton = document.getElementById('add-ingredient');
        const removeIngredientButton = document.getElementById('remove-ingredient');

        // Agregar una fila de ingredientes
        addIngredientButton.addEventListener('click', () => {
            const ingredientRow = document.createElement('div');
            ingredientRow.classList.add('ingredient-row');

            ingredientRow.innerHTML = `
                <input type="number" name="quantity[]" placeholder="Cantidad" min="0" required>
                <select name="unit[]" required>
                    <option value="tazas">Tazas</option>
                    <option value="oz">Onzas</option>
                    <option value="lb">Libras</option>
                    <option value="ml">Mililitros</option>
                    <option value="l">Litros</option>
                    <option value="gr">Gramos</option>
                    <option value="kg">Kilogramos</option>
                    <option value="Cda">Cucharada</option>
                    <option value="cdta">Cucharadita</option>
                    <option value="u">Unidades</option>
                </select>
                <input type="text" name="ingredient[]" placeholder="Ingrediente" required>
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
    </script>
</body>
</html>

<?php
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require 'layout.php';
?>