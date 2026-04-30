<?php
require 'config.php';

$messaggio = '';

if (isset($_GET['ok'])) {
    $messaggio = '<div class="msg-ok">Restituzione registrata con successo!</div>';
} elseif (isset($_GET['errore'])) {
    $messaggio = '<div class="msg-err">ERRORE: Prestito non trovato o già restituito.</div>';
}

// Carica utenti per menu a tendina
$utenti = $pdo->query("SELECT id_utente, nome, cognome FROM Utenti ORDER BY cognome, nome")->fetchAll();

$id_utente_sel = intval($_GET['id_utente'] ?? 0);
$prestiti = [];

if ($id_utente_sel > 0) {
    $stmt = $pdo->prepare("
        SELECT p.id_prestito, l.titolo, a.nome AS autore_nome, a.cognome AS autore_cognome,
               p.data_inizio, p.data_fine_prevista, p.restituito
        FROM Prestiti p
        JOIN Libri l ON p.id_libro = l.id_libro
        LEFT JOIN Autori a ON l.id_autore = a.id_autore
        WHERE p.id_utente = ?
        ORDER BY p.data_inizio DESC
    ");
    $stmt->execute([$id_utente_sel]);
    $prestiti = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Libri per Utente</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>


<h1>LIBRI IN PRESTITO PER UTENTE</h1>
<p><a href="index.html">← HOME</a></p>

<?= $messaggio ?>

<div class="box">
    <form method="GET" action="libri_utente.php">
        <label for="id_utente">Seleziona utente:</label>
        <select id="id_utente" name="id_utente">
            <option value="">-- seleziona --</option>
            <?php foreach ($utenti as $u): ?>
                <option value="<?= $u['id_utente'] ?>" <?= ($u['id_utente'] == $id_utente_sel) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['cognome'] . ' ' . $u['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="MOSTRA PRESTITI">
    </form>
</div>

<?php if ($id_utente_sel > 0): ?>
<div class="box">
    <h2>Prestiti trovati: <?= count($prestiti) ?></h2>

    <?php if (empty($prestiti)): ?>
        <p>Nessun prestito trovato per questo utente.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titolo</th>
                    <th>Autore</th>
                    <th>Data inizio</th>
                    <th>Data fine prevista</th>
                    <th>Restituito</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($prestiti as $p): ?>
                <tr>
                    <td><?= $p['id_prestito'] ?></td>
                    <td><?= htmlspecialchars($p['titolo']) ?></td>
                    <td><?= htmlspecialchars($p['autore_cognome'] . ' ' . $p['autore_nome']) ?></td>
                    <td><?= $p['data_inizio'] ?></td>
                    <td><?= $p['data_fine_prevista'] ?></td>
                    <td>
                        <?php if ($p['restituito']): ?>
                            <span class="stato-si">SI</span>
                        <?php else: ?>
                            <span class="stato-no">NO</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$p['restituito']): ?>
                            <form method="POST" action="restituisci.php">
                                <input type="hidden" name="id_prestito" value="<?= $p['id_prestito'] ?>">
                                <input type="hidden" name="id_utente" value="<?= $id_utente_sel ?>">
                                <button type="submit" class="btn-restituisci">RESTITUISCI</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php endif; ?>

</body>
</html>
