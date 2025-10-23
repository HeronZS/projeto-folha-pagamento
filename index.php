<?php

require_once 'config/config.php';
require_once 'models/FolhaPagamento.php';
require_once 'controllers/FolhaController.php';

$controller = new FolhaController();
$controller->exibir();
?>