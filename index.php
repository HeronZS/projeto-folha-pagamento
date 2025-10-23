<?php

/**
 * Autoload simples (ou require manual)
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */
require_once __DIR__ . '/controllers/PayrollController.php';

$controller = new PayrollController();
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->processPayroll();
}

$controller->render('payroll_view', ['result' => $result]);
