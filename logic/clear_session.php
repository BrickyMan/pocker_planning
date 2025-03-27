<?php
session_start();
session_unset();
session_destroy();
header("Location: /pocker_planing/login");
exit();
?>