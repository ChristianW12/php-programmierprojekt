<?php
session_start();
session_destroy();
header('Location: angebote.php');
exit;