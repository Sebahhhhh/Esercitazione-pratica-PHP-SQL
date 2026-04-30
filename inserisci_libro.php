<?php
require 'config.php';

$messaggio = '';

// Gestione POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolo = trim($_POST['titolo'] ?? '');
    $anno   = intval($_POST['anno_pubblicazione'] ?? 0);
    $isbn   = trim($_POST['isbn'] ?? '');
    $id_autore = intval($_POST['id_autore'] ?? 0);

    if ($titolo === '' || $id_autore === 0) {
        $messaggio = '<div class="msg-err">ERRORE: Titolo e autore sono obbligatori.</div>';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Libri (titolo, anno_pubblicazione, isbn, id_autore) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $titolo,
                $anno ?: null,
                $isbn ?: null,
                $id_autore
            ]);
            $messaggio = '<div class="msg-ok">Libro inserito con successo! (ID: ' . $pdo->lastInsertId() . ')</div>';
        } catch (PDOException $e) {
            $messaggio = '<div class="msg-err">ERRORE DB: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Carica autori per il menu a tendina
$autori = $pdo->query("SELECT id_autore, nome, cognome FROM Autori ORDER BY cognome, nome")->fetchAll();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Inserisci Libro</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>


<h1>INSERISCI NUOVO LIBRO</h1>
<p><a href="index.html">← HOME</a></p>

<?= $messaggio ?>

<div class="box">
    <form method="POST" action="inserisci_libro.php">

        <label for="titolo">Titolo *</label>
        <input type="text" id="titolo" name="titolo" required>

        <label for="id_autore">Autore *</label>
        <select id="id_autore" name="id_autore" required>
            <option value="">-- seleziona autore --</option>
            <?php foreach ($autori as $a): ?>
                <option value="<?= $a['id_autore'] ?>">
                    <?= htmlspecialchars($a['cognome'] . ' ' . $a['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="anno_pubblicazione">Anno di pubblicazione</label>
        <input type="number" id="anno_pubblicazione" name="anno_pubblicazione" min="1000" max="2100">

        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn">

        <br>
        <input type="submit" value="INSERISCI LIBRO">
    </form>
</div>

</body>
</html>
