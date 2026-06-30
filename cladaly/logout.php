<?php
session_start();
include "funcoes.php";
session_destroy();
redirecionar("main.php");
exit;