<?php
require 'config.php';

$messaggio = '';

// Gestione POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_libro   = intval($_POST['id_libro'] ?? 0);
    $id_utente  = intval($_POST['id_utente'] ?? 0);
    $data_inizio = trim($_POST['data_inizio'] ?? '');
    $data_fine   = trim($_POST['data_fine_prevista'] ?? '');

    if ($id_libro === 0 || $id_utente === 0 || $data_inizio === '' || $data_fine === '') {
        $messaggio = '<div class="msg-err">ERRORE: Tutti i campi sono obbligatori.</div>';
    } elseif ($data_fine <= $data_inizio) {
        $messaggio = '<div class="msg-err">ERRORE: La data di fine deve essere successiva alla data di inizio.</div>';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Prestiti (id_libro, id_utente, data_inizio, data_fine_prevista, restituito) VALUES (?, ?, ?, ?, FALSE)");
            $stmt->execute([$id_libro, $id_utente, $data_inizio, $data_fine]);
            $messaggio = '<div class="msg-ok">Prestito registrato con successo! (ID: ' . $pdo->lastInsertId() . ')</div>';
        } catch (PDOException $e) {
            $messaggio = '<div class="msg-err">ERRORE DB: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Carica libri e utenti per i menu a tendina
$libri   = $pdo->query("SELECT id_libro, titolo FROM Libri ORDER BY titolo")->fetchAll();
$utenti  = $pdo->query("SELECT id_utente, nome, cognome FROM Utenti ORDER BY cognome, nome")->fetchAll();

$oggi = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Inserisci Prestito</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>


<h1>INSERISCI NUOVO PRESTITO</h1>
<p><a href="index.html">← HOME</a></p>

<?= $messaggio ?>

<div class="box">
    <form method="POST" action="inserisci_prestito.php">

        <label for="id_libro">Libro *</label>
        <select id="id_libro" name="id_libro" required>
            <option value="">-- seleziona libro --</option>
            <?php foreach ($libri as $l): ?>
                <option value="<?= $l['id_libro'] ?>">
                    <?= htmlspecialchars($l['titolo']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="id_utente">Utente *</label>
        <select id="id_utente" name="id_utente" required>
            <option value="">-- seleziona utente --</option>
            <?php foreach ($utenti as $u): ?>
                <option value="<?= $u['id_utente'] ?>">
                    <?= htmlspecialchars($u['cognome'] . ' ' . $u['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="data_inizio">Data inizio *</label>
        <input type="date" id="data_inizio" name="data_inizio" value="<?= $oggi ?>" required>

        <label for="data_fine_prevista">Data fine prevista *</label>
        <input type="date" id="data_fine_prevista" name="data_fine_prevista" required>

        <br>
        <input type="submit" value="REGISTRA PRESTITO">
    </form>
</div>

</body>
</html>
