<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: libri_utente.php');
    exit;
}

$id_prestito = intval($_POST['id_prestito'] ?? 0);
$id_utente   = intval($_POST['id_utente'] ?? 0);

if ($id_prestito === 0) {
    die("ID prestito non valido.");
}

try {
    $stmt = $pdo->prepare("UPDATE Prestiti SET restituito = TRUE WHERE id_prestito = ? AND restituito = FALSE");
    $stmt->execute([$id_prestito]);

    if ($stmt->rowCount() === 0) {
        // Prestito non trovato o già restituito
        header("Location: libri_utente.php?id_utente=$id_utente&errore=1");
    } else {
        header("Location: libri_utente.php?id_utente=$id_utente&ok=1");
    }
    exit;

} catch (PDOException $e) {
    die("Errore DB: " . htmlspecialchars($e->getMessage()));
}
?>
