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
        $sql = "SELECT nome_utente FROM UTENTE WHERE nome_utente = '$nome'";
        $stmt = $pdo->query($sql);
        $utente = $stmt->fetch();

        if ($utente !== false) {
            $messaggio = 'Questo nome utente è già in uso.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO UTENTE (nome_utente, password_hash) VALUES ('$nome', '$hash')";
            $pdo->query($sql);

            $messaggio = 'Registrazione completata con successo. Reindirizzamento in corso...';
            header('Refresh: 2; url=index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Community di Minecraft - Registrazione</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require_once __DIR__ . '/nav.php'; ?>
    <h1>Effettua la Registrazione</h1>

    <?php if ($messaggio !== '' && $messaggio !== 'Utente registrato con successo' && $messaggio !== 'Registrazione completata con successo. Reindirizzamento in corso...'): ?>
        <p style="color: red;"><?= $messaggio ?></p>
    <?php elseif ($messaggio !== ''): ?>
        <p style="color: green;"><?= $messaggio ?></p>
    <?php endif; ?>

    <form action="registrazione.php" method="post">
        <p>
            <label for="nome_utente">Nome Utente:</label><br>
            <input type="text" id="nome_utente" name="Nome_Utente" required>
        </p>
        <p>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="Password" required>
        </p>
        <p>
            <input type="submit" value="Registrati">
        </p>
    </form>
</body>
</html>