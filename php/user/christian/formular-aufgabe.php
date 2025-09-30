<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aufgabe Formular Übung</title>
</head>
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
    }
    input{
        margin-top: 10px;
    }
</style>
<body>
<form method="POST">
    <div align="center" >
        <h1>Login</h1>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <br>
        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" >
        <br>
        <button type="submit" style="margin-top: 10px;">Absenden</button>
    </div>
</form>
</body>
</html>