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

$sql = "SELECT * FROM RICETTE WHERE Id = " . $id; // query sql per selezionare la ricetta con l'ID specificato
$stmt = $pdo->query($sql); // invia la query al DB tramite oggetto pdo e la fa eseguire, ritorno di tipo PDOStatement
$RICETTA = $stmt->fetch();
// fetch() e non fetchAll() perche la query resituisce al massimo una riga (quella dell'ID specificato)
// quindi non serve fetchAll() che ritorna un array di righe, ma solo fetch() che ritorna la riga singola come array associativo
// se la query non ritorna righe, fetch() ritorna false, quindi controllo se RICETTA è false
if ($RICETTA === false) {
    header('Location: index.php');
    exit;
}

if ($RICETTA['Autore'] !== $utenteCorrente) {
    die('Non hai i permessi per modificare questa ricetta.');
}

$messaggio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // questo metodo riporta il tipo di richiesta HTTP fatta dal browser
                                            // In questo caso controlla se è POST ovvero se l'utente ha inviato il form per salvare la ricetta
    $nome = trim($_POST['Nome'] ?? ''); // legge il valore del campo Nome, con ?? '' se non esiste, e con trim() rimuove spazi iniziali e finali
    $ingredienti = trim($_POST['Ingredienti'] ?? '');
    $disposizione = trim($_POST['Disposizione'] ?? '');

    if ($nome === '' || $ingredienti === '' || $disposizione === '') {
        $messaggio = 'Compila tutti i campi prima di salvare la ricetta.';
    } else {
        $sql = "UPDATE RICETTE SET Nome = '$nome', Ingredienti = '$ingredienti', Disposizione = '$disposizione' WHERE Id = $id AND Autore = '$utenteCorrente'";
        $pdo->query($sql); // invia al DB la query e la fa eseguire salvando cosi la nuova rcetta

        $messaggio = 'Modifica della ricetta salvata con successo';
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Community di Minecraft - Modifica Ricetta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Modifca Ricetta</h1>

    <?php if ($messaggio !== '' && $messaggio !== 'Modifica della ricetta salvata con successo'): ?>
        <p style="color: red;"><?= $messaggio ?></p>
    <?php elseif ($messaggio !== ''): ?>
        <p style="color: green;"><?= $messaggio ?></p>
    <?php endif; ?>

    <form action="modifica.php?id=<?= urlencode((string) $id) ?>" method="post">
        <p>
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="Nome" value="<?= htmlspecialchars($RICETTA['Nome']) ?>" required>
        </p>
        <p>
            <label for="ingredienti">Ingredienti:</label><br>
            <textarea id="ingredienti" name="Ingredienti" rows="4" cols="40" required><?= htmlspecialchars($RICETTA['Ingredienti']) ?></textarea>
        </p>
        <p>
            <label for="disposizione">Disposizione:</label><br>
            <textarea id="disposizione" name="Disposizione" rows="4" cols="40" required><?= htmlspecialchars($RICETTA['Disposizione']) ?></textarea>
        </p>
        <button type="submit">Salva modifiche ricetta</button>

        <p><a href="index.php">Torna all'elenco ricette</a></p>
    </form>
    
</body>
</html>