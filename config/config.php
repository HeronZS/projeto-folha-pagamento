<?php

/**
 * Configurações gerais do sistema
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */

// Configurações de timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de exibição de erros (desabilitar em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Constantes do sistema
define('SISTEMA_NOME', 'Sistema de Folha de Pagamento');
define('SISTEMA_VERSAO', '1.0');

// Tabelas de INSS e IR (podem ser movidas para banco de dados futuramente)
define('TABELA_INSS', [
    ['limite' => 1412.00, 'aliquota' => 0.075],
    ['limite' => 2666.68, 'aliquota' => 0.09],
    ['limite' => 4000.03, 'aliquota' => 0.12],
    ['limite' => 7786.02, 'aliquota' => 0.14]
]);

define('TABELA_IR', [
    ['limite' => 2259.20, 'aliquota' => 0.0, 'deducao' => 0],
    ['limite' => 2826.65, 'aliquota' => 0.075, 'deducao' => 169.44],
    ['limite' => 3751.05, 'aliquota' => 0.15, 'deducao' => 381.44],
    ['limite' => 4664.68, 'aliquota' => 0.225, 'deducao' => 662.77],
    ['limite' => PHP_FLOAT_MAX, 'aliquota' => 0.275, 'deducao' => 896.00]
]);

define('TETO_INSS', 908.85);
?>