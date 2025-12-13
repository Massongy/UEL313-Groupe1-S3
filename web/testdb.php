<?php
// testdb.php

try {
    // Connexion à la base de données avec les mêmes paramètres que dans dev.php
    $db = new PDO('mysql:host=localhost;dbname=watson;charset=utf8', 'watson', 'watson');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion à la base réussie !<br>";

    // Test simple : récupérer les liens
    $stmt = $db->query("SELECT lien_id, lien_titre, lien_url FROM tl_liens ORDER BY lien_id DESC LIMIT 15");
    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($links) > 0) {
        echo "Voici les 15 derniers liens :<br>";
        foreach ($links as $link) {
            echo "ID: {$link['lien_id']}, Titre: {$link['lien_titre']}, URL: {$link['lien_url']}<br>";
        }
    } else {
        echo "Aucun lien trouvé dans la base.";
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
