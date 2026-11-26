<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav>
    <a href="index.php">Elenco Ricette</a>
    <?php if (isset($_SESSION['utente'])): ?>
        <a href="aggiungi.php">Aggiungi Ricetta</a>
        <span>Utente: <?= htmlspecialchars($_SESSION['utente']) ?></span>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="registrazione.php">Registrati</a>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>