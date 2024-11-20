<?php
    $senha = "joao12345";
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    echo $hash;
?>
