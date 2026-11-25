
<?php
//richiedo una volta il file db.php che si trova su questa directory (__DIR__) cosi lo eseguo e creo l'oggetto di connessione $pdo 
//poi lo includo in questo file per poter usare la connessione al database
//require_once serve perche se il comando non va a buon fine PHP gestisce l'errore come specificato nel file db.php
require_once __DIR__ . '/db.php'; 
$sql = "SELECT Id, Nome, Ingredienti, Disposizione FROM RICETTE"; // query SQL per selezionare tutte le colonne dalla tabella RICETTE
$stmt = $pdo->query($sql); //ritorna un oggetto PDOStatement che rappresenta il risultato della query ($sql) eseguita da MySQL !!!NON sono righe pronte all'uso
$RICETTE = $stmt->fetchAll(); //uso metodo fetchAll() per ottenere tutte le righe del risultato della query come array associativo e lo salvo nella variabile $RICETTE
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Community di Mincraft - Ricette</title>
</head>
    <body>
        <?php require_once __DIR__ . '/nav.php'; //includo il file nav.php per avere il menu di navigazione in tutte le pagine ?>
        <h1>Elenco Ricette</h1>

        <table border="1" cellpadding="8"> <!-- crea una tabella con bordo di 1px e padding di 8px per le celle !!!POI DA SOSTITUIRE CON CSS A PARTE -->
            <thead>
                <tr> <!-- definisce la riga -->
                    <th>ID</th> <!-- definisce le singole celle mette il testo centrato e in grassetto -->
                    <th>Nome</th>
                    <th>Ingredienti</th>
                    <th>Disposizione</th>
                    <th>Azioni</th>
                </tr>
            </thead>
                         

            <tbody> <!-- indica il corpo della tabella dove andranno inserite le righe generate dinamicamente -->
                <?php foreach ($RICETTE as $RICETTA): ?> <!-- Prendo una $RICETTA alla volta da $RICETTE -->
                        <tr>
                            <td><?= htmlspecialchars($RICETTA['Id']) ?></td> <!-- trasforma il valore della colonna 'Id' in una stringa sicura per l'HTML e lo stampa nella cella -->
                            <td><?= htmlspecialchars($RICETTA['Nome']) ?></td>
                            <td><?= nl2br(htmlspecialchars($RICETTA['Ingredienti'])) ?></td> <!-- nl2br() prende i caratteri di nuova riga e li trasforma in tag <br> per mantenere la giusta formattazione -->
                            <td><?= nl2br(htmlspecialchars($RICETTA['Disposizione'])) ?></td>
                            <td>
                                <a href="elimina.php?id=<?= urlencode((string) $RICETTA['Id']) ?>">Elimina</a> 
                                <br>
                                <a href="modifica.php?id=<?= urlencode((string) $RICETTA['Id']) ?>">Modifica</a>
                            </td> 
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </body>
</html>
