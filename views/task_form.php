<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Receta</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #e8f5e9;
            --text-color: #333;
            --border-radius: 15px;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f8f1;
            color: var(--text-color);
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, 
                rgba(255,255,255,0.1) 0%, 
                rgba(255,255,255,0.2) 100%);
            transform: skewY(-6deg);
            transform-origin: top left;
        }

        .header h1 {
            position: relative;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            font-size: 2.8rem;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .recipe-form {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            outline: none;
        }

        .ingredient-row {
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: start;
        }

        .step-row {
            margin-bottom: 1rem;
        }

        .btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--accent-color);
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 1rem 0;
        }

        .file-upload {
            background: var(--accent-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            border: 2px dashed var(--primary-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            background: #dff0d8;
            border-color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .ingredient-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-utensils"></i> Agregar Receta</h1>
    </div>

    <div class="container">
        <form class="recipe-form" action="<?php echo BASE_URL; ?>/index.php?action=store" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="recipe-name"><i class="fas fa-pencil"></i> Nombre de la receta:</label>
                <input type="text" id="recipe-name" name="recipe_name" placeholder="Ej. Pastel de chocolate" maxlength="100" required>
            </div>

            <div class="form-group">
                <div class="file-upload">
                    <i class="fas fa-camera fa-2x"></i>
                    <h3>Fotos de la Receta</h3>
                    <input type="file" name="recipe_images[]" id="recipe_images" multiple accept="image/*">
                </div>
            </div>

            <div class="form-group">
                <label for="prep-time"><i class="fas fa-clock"></i> Tiempo de preparación (en minutos):</label>
                <input type="number" id="prep-time" name="prep_time" min="1" max="500" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-list"></i> Ingredientes:</label>
                <div id="ingredients-container">
                    <div class="ingredient-row">
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
                    </div>
                </div>
                <div class="actions">
                    <button type="button" class="btn" id="add-ingredient"><i class="fas fa-plus"></i> Más</button>
                    <button type="button" class="btn btn-outline" id="remove-ingredient"><i class="fas fa-minus"></i> Menos</button>
                </div>
            </div>

            <div class="form-group">
                <label for="steps"><i class="fas fa-tasks"></i> Pasos a seguir:</label>
                <div id="steps-container">
                    <div class="step-row">
                        <textarea name="steps[]" rows="3" placeholder="Describe un paso" required></textarea>
                    </div>
                </div>
                <div class="actions">
                    <button type="button" class="btn" id="add-step"><i class="fas fa-plus"></i> Más</button>
                    <button type="button" class="btn btn-outline" id="remove-step"><i class="fas fa-minus"></i> Menos</button>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-info-circle"></i> Descripción:</label>
                <textarea id="description" name="description" rows="3" placeholder="Ej. Receta especial para fiestas" required></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn" id="submit-button"><i class="fas fa-save"></i> Guardar Receta</button>
            </div>
        </form>
    </div>

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