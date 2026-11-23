
<?php
    //apertura della connessione al database MySQL con PDO
    $dsn = "mysql:host=db;dbname=CommunityDB;charset=utf8mb4"; //usa diver mysql, collegarsi all'host 'db', selezionare database 'CommunityDB' e usare la codifica utf8mb4
    $utente = "Admin";
    $password = "Admin";
    $opzioni = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //trasforma gli errori in eccezioni per poterle gestire
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //trasforma le righe di ritorno in array associativi
    ];

    // TRY: prava a creare l'oggetto di connessione $pdo CATCH: se non riesce cattura l'eccezione e mostra il messaggio di errore
    try {
        $pdo = new PDO($dsn, $utente, $password, $opzioni);
    } catch (PDOException $e) {
        die("Errore di connessione: " . htmlspecialchars($e->getMessage()));
    }
?>