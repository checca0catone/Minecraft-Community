<?php
session_start();
require_once __DIR__ . '/db.php';

if (isset($_SESSION['utente'])) {
    header('Location: index.php');
    exit;
}

$messaggio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['Nome_Utente'] ?? '');
    $password = trim($_POST['Password'] ?? '');

    if ($nome === '' || $password === '') {
        $messaggio = 'Compila nome utente e password.';
    } else {
        $sql = "SELECT nome_utente, password_hash FROM UTENTE WHERE nome_utente = '$nome'";
        $stmt = $pdo->query($sql);
        $utente = $stmt->fetch();

        if ($utente && password_verify($password, $utente['password_hash'])) {
            $_SESSION['utente'] = $utente['nome_utente'];
            header('Location: index.php');
            exit;
        } else {
            $messaggio = 'Nome utente o password non validi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <title>Community di Minecraft - Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php require_once __DIR__ . '/nav.php'; ?>
        <h1>Effettua il Login</h1>

        <?php if ($messaggio !== ''): ?>
            <p style="color: red;"><?= htmlspecialchars($messaggio) ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <p>
                <label for="nome_utente">Nome Utente:</label><br>
                <input type="text" id="nome_utente" name="Nome_Utente" required>
            </p>
            <p>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="Password" required>
            </p>
            <p>
                <input type="submit" value="Login">
            </p>
        </form>
    </body>
</html>