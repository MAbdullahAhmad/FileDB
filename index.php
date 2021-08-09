<?php
  require "./file_db.php";

  // Create Example FileDB
  $users = new FileDB('./example.fdb', ['id', 'name']);

  // Add Record
  $users->add(['1', 'name']); // New User

  // Fetch & Print Record
  $users->iter();
  $record = $users->next();
  echo "<pre>"; print_r($record); echo "</pre>";

  // Update Record
  $users->upd(['id'=>2, 'name'=>'newName']);
?>
