<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit;
}

$utenteCorrente = $_SESSION['utente'];
$idRaw = $_GET['id'] ?? ''; // legge il parametro ID arrivato nell'URL, se non esiste assegna una strina vuota tramite operatore ?? e ''
$id = filter_var($idRaw, FILTER_VALIDATE_INT); // controlla se il valore di idRaw è un intero valido

if ($id === false || $id <= 0) { // controlla se id non valido ( = false) oppure <= 0 (partono da 1 in su)
    header('Location: index.php'); // riporta l'utente alla pagina index.php
    exit; //chiude l'esecuzione del codice php
}

$sqlSelect = "SELECT Autore FROM RICETTE WHERE Id = " . $id;
$stmtSelect = $pdo->query($sqlSelect);
$ricetta = $stmtSelect->fetch();

if ($ricetta === false) {
    header('Location: index.php');
    exit;
}

if ($ricetta['Autore'] !== $utenteCorrente) {
    die('Non hai i permessi per eliminare questa ricetta.');
}

$sql = "DELETE FROM RICETTE WHERE Id = " . $id . " AND Autore = '$utenteCorrente'"; // query sql per eliminare la ricetta
$stmt = $pdo->query($sql); // invia la query al DB tramite oggetto pdo e la fa eseguire, ritorno di tipo PDOStatement

if ($stmt->rowCount() > 0) { // conta righe eliminate, se > 0 allora la ricetta è stata eliminata
    $messaggio = 'Ricetta eliminata con successo.';
} else {
    $messaggio = 'Nessuna ricetta trovata con questo ID.';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Community di Minecraft - Elimina Ricetta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Elimina Ricetta</h1>
    <p><?= htmlspecialchars($messaggio) ?></p>
    <p><a href="index.php">Torna all'elenco ricette</a></p>
</body>
</html>