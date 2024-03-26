

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solicitud de Eliminación de Datos</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-weight: bold;
    }
    input[type="text"], input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    @media screen and (max-width: 600px) {
        .container {
            width: 90%;
        }
    }
</style>
</head>
<body>

<div class="container">
    <img src="https://s3.amazonaws.com/cdn.meat.cl/mailing/integra/logo+integra.png" alt="Logo" style="display:block; margin: 0 auto; max-width: 100%;">
    <h2>Solicitud de Eliminación de Datos</h2>
    <form method="POST" action="{{ route('desastivar.usuario') }}">
        <div class="form-group">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" required>
        </div>
        <input type="submit" value="Solicitar Eliminación">
    </form>
</div>

</body>
</html>