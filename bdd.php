<?php
$linkDB = mysqli_connect('localhost', 'root', '', 'orsete_appmanga')
or die ("Vous n'avez pas pu vous connecter à la BDD : " .mysqli_connect_error() . mysqli_connect_errno()); 

mysqli_set_charset($linkDB, "utf8");
?>