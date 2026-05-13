<?php
// Script pour initialiser la base de données SQLite3

$dbPath = __DIR__ . '/writable/database.db';

try {
    // Créer la connexion SQLite3
    $db = new SQLite3($dbPath);
    $db->busyTimeout(5000);
    
    echo "✓ Base de données créée: " . $dbPath . "\n";
    
    // Lire le fichier SQL
    $sqlFile = __DIR__ . '/app/Database/Migrations/Database.db';
    $sql = file_get_contents($sqlFile);
    
    // Diviser les requêtes par point-virgule et les exécuter
    $queries = explode(';', $sql);
    $count = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $db->exec($query);
            $count++;
        }
    }
    
    echo "✓ $count requêtes SQL exécutées avec succès!\n";
    echo "✓ Tables créées\n";
    echo "✓ Données d'exemple insérées\n";
    
    $db->close();
    echo "\n✓ Base de données SQLite3 prête!\n";
    
} catch (Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
