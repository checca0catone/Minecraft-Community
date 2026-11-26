<?php
session_start();

//richiedo una volta il file db.php che si trova su questa directory (__DIR__) cosi lo eseguo e creo l'oggetto di connessione $pdo 
//poi lo includo in questo file per poter usare la connessione al database
//require_once serve perche se il comando non va a buon fine PHP gestisce l'errore come specificato nel file db.php
require_once __DIR__ . '/db.php'; 

if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit;
}

$autore = $_SESSION['utente'];
$messaggio = ''; //variabile ora vuota ma che uso poi per salvare messaggi di errore

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // questo metodo riporta il tipo di richiesta HTTP fatta dal browser
                                            // In questo caso controlla se è POST ovvero se l'utente ha inviato il form per salvare la ricetta
    $nome = trim($_POST['Nome'] ?? ''); // legge il valore del campo Nome, con ?? '' se non esiste, e con trim() rimuove spazi iniziali e finali
    $ingredienti = trim($_POST['Ingredienti'] ?? '');
    $disposizione = trim($_POST['Disposizione'] ?? '');

    if ($nome === '' || $ingredienti === '' || $disposizione === '') {
        $messaggio = 'Compila tutti i campi prima di salvare la ricetta.';
    } else {
        $sql = "INSERT INTO RICETTE (Nome, Ingredienti, Disposizione, Autore) VALUES ('$nome', '$ingredienti', '$disposizione', '$autore')";
        $pdo->query($sql); // invia al DB la query e la fa eseguire salvando cosi la nuova rcetta

        $messaggio = 'Ricetta salvata con successo';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Community di Minecraft - Aggiungi Ricetta</title>
</head>
    <body>
        <?php require_once __DIR__ . '/nav.php'; ?>

        <h1>Nuova Ricetta</h1>

        <?php if ($messaggio !== '' && $messaggio !== 'Ricetta salvata con successo'): ?>
            <p style="color: red;"><?= $messaggio ?></p>
        <?php elseif ($messaggio !== ''): ?>
            <p style="color: green;"><?= $messaggio ?></p>
        <?php endif; ?>

        <form action="aggiungi.php" method="post">
            <p>
                <label for="nome">Nome:</label><br>
                <input type="text" id="nome" name="Nome" required>
            </p>
            <p>
                <label for="ingredienti">Ingredienti:</label><br>
                <textarea id="ingredienti" name="Ingredienti" rows="4" cols="40" required></textarea>
            </p>
            <p>
                <label for="disposizione">Disposizione:</label><br>
                <textarea id="disposizione" name="Disposizione" rows="4" cols="40" required></textarea>
            </p>
            <button type="submit">Salva Ricetta</button>
        </form>
    </body>
</html>