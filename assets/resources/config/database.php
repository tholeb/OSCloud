<?php
  $username = "admin";
  $password = "root";
  $hostname = "localhost";
  $namebase = "oscloud";

  try
  {
    $admin = new PDO('mysql:host='.$hostname.';dbname='.$namebase.'', $username, $password);
    $admin->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (PDOException $e)
  {
    die('Erreur : ' . $e->getMessage());
    echo 'Échec lors de la connexion ' . $namebase . ': ' . $e->getMessage();
  }