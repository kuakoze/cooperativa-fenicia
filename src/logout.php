<?php
// coge la sesión actual, primero la limpia y luego la destruye
//y te redirige a index.php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();