<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adivina el número</title>
    <link rel="stylesheet" href="adivina.css"> <!-- Enlace al archivo CSS -->
</head>
<body>
    <div class="container">
        <h1>Adivina el Número</h1>
        <p>Intenta adivinar el número entre <strong>0</strong> y <strong>1000</strong>.</p>

        <form action="" method="POST">
            <label for="numero">Introduce tu número:</label>
            <input type="number" id="numero" name="numero" min="0" max="1000" required>
            <div class="button-container">
                <button type="submit">Adivinar</button>
                <button type="submit" name="reiniciar" formnovalidate>Reiniciar</button>
            </div>
        </form>

        <div class="result-container">
            <?php
            session_start();

            // Inicia la sesión si no existe
            if (!isset($_SESSION['secret_random_number'])) {
                $_SESSION['secret_random_number'] = mt_rand(0, 1000);
                $_SESSION['intentos'] = [];
            }

            $adivinar = $_SESSION['secret_random_number'];  // El número a adivinar
            $intentos = $_SESSION['intentos'];  // Lista de intentos

            // Si se presiona el botón "Reiniciar", reseteamos la sesión
            if (isset($_POST['reiniciar'])) {
                unset($_SESSION['secret_random_number']);
                $_SESSION['intentos'] = [];
                header('Location: ' . $_SERVER['PHP_SELF']);  // Redirigimos para evitar resubir el formulario
                exit;
            }

            // Si se introduce un número
            if (isset($_POST['numero'])) {
                $numero = intval($_POST['numero']);

                // Validación: número debe estar entre 0 y 1000
                if ($numero < 0 || $numero > 1000) {
                    echo "<p class='error'>Por favor, introduce un número entre 0 y 1000.</p>";
                } else {
                    // Guardamos el intento
                    $_SESSION['intentos'][] = $numero;

                    // Comprobamos si el número es mayor, menor o si se ha adivinado
                    if ($numero < $adivinar) {
                        echo "<p class='hint'>Más alto.</p>";
                    } elseif ($numero > $adivinar) {
                        echo "<p class='hint'>Más bajo.</p>";
                    } else {
                        echo "<p class='success'>¡Enhorabuena! Has adivinado el número en " . count($_SESSION['intentos']) . " intentos.</p>";
                        unset($_SESSION['secret_random_number']);  // Reseteamos el número secreto tras adivinarlo
                    }
                }
            }

            // Mostramos la lista de intentos fallidos
            if (!empty($_SESSION['intentos'])) {
                echo "<p class='attempts'>Intentos fallidos: " . implode(", ", $_SESSION['intentos']) . "</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
