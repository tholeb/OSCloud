<?php
  $username = "admin";
  $password = "root";
  $hostname = "localhost";
  $namebase = "oscloud";

  try
  {
    $pdo = new PDO("mysql:host=$hostname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'oscloud'");
    if ((bool) $stmt->fetchColumn() == 0) {
      $sql = file_get_contents(__DIR__.'/oscloud.sql');
      $qr = $pdo->exec($sql);
    }
  }
  catch (PDOException $e)
  {
    die('Erreur : ' . $e->getMessage());
    echo 'Échec lors de la connexion ' . $namebase . ': ' . $e->getMessage();
  }

  // $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'oscloud'");
  // return (bool) $pdo->fetchColumn();
