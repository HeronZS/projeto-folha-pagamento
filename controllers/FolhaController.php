<?php

/**
 * Controller Responsável por processar as requisições e conectar Model e View
 * @author Heron Zonta da Silva
 * @since 23/10/2025s
 */

class FolhaController {
    
    private $model;
    
    public function __construct() {
        $this->model = new FolhaPagamento();
    }
    
    /**
     * Processa a requisição e prepara dados para a view
     */
    public function processar() {
        $resultado = null;
        $erro = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $salarioBruto = $this->validarNumero($_POST['salario'] ?? '');
                $beneficios = $this->validarNumero($_POST['beneficios'] ?? 0);
                $outrosDescontos = $this->validarNumero($_POST['outros_descontos'] ?? 0);
                
                $resultado = $this->model->calcularFolha($salarioBruto, $beneficios, $outrosDescontos);
                
            } catch (Exception $e) {
                $erro = $e->getMessage();
            }
        }
        
        return [
            'resultado' => $resultado,
            'erro' => $erro
        ];
    }
    
    /**
     * Valida e converte valor numérico
     * @param mixed $valor
     * @return float
     * @throws Exception Se o valor não for numérico
     */
    private function validarNumero($valor) {
        $valor = str_replace(',', '.', trim($valor));
        
        if (!is_numeric($valor)) {
            throw new Exception("Valor inválido. Informe um número válido.");
        }
        
        return (float) $valor;
    }
    
    /**
     * Exibe a view principal
     */
    public function exibir() {
        $dados = $this->processar();
        require_once 'views/FolhaView.php';
    }
}
?>