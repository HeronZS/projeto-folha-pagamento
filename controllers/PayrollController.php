<?php

require_once 'models/Employee.php';
require_once 'models/PayrollCalculator.php';

/**
 * Classe PayrollController - Gerencia a lógica de controle
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */
class PayrollController {
    
    /**
     * Processa a requisição POST do formulário
     * @return array - Resultado do processamento ou erros
     */
    public function processPayroll(): array {
        // Verifica se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'success' => false,
                'error' => 'Método de requisição inválido'
            ];
        }
        
        // Obtém dados do POST de forma imutável
        $rawData = $this->getPostData();
        
        // Valida os dados de entrada
        $validation = $this->validateInput($rawData);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'error' => $validation['message']
            ];
        }
        
        // Cria array de funcionários de forma imutável
        $employees = $this->createEmployeesFromData($rawData);
        
        // Processa a folha de pagamento (função pura)
        $payrollResults = PayrollCalculator::processMultipleEmployees($employees);
        
        // Agrupa por centro de custo (função pura)
        $costCenterTotals = PayrollCalculator::groupByCostCenter($payrollResults);
        
        return [
            'success' => true,
            'payroll_results' => $payrollResults,
            'cost_center_totals' => $costCenterTotals
        ];
    }
    
    /**
     * Função pura: Obtém dados do POST de forma estruturada
     * @return array - Dados estruturados
     */
    private function getPostData(): array {
        return [
            'employees' => $_POST['employees'] ?? []
        ];
    }
    
    /**
     * Função pura: Valida entrada do usuário
     * @param array $data - Dados a serem validados
     * @return array - Resultado da validação
     */
    private function validateInput(array $data): array {
        if (empty($data['employees'])) {
            return [
                'valid' => false,
                'message' => 'Nenhum funcionário foi informado'
            ];
        }
        
        // Filtra funcionários válidos usando função de ordem superior
        $validEmployees = PayrollCalculator::filterValidEmployees($data['employees']);
        
        if (empty($validEmployees)) {
            return [
                'valid' => false,
                'message' => 'Nenhum funcionário com dados válidos foi encontrado. Verifique se todos os campos estão preenchidos corretamente.'
            ];
        }
        
        // Valida cada salário individualmente
        foreach ($validEmployees as $emp) {
            $salary = floatval($emp['grossSalary']);
            if (!PayrollCalculator::isValidSalary($salary)) {
                return [
                    'valid' => false,
                    'message' => "Salário inválido para {$emp['name']}. O salário deve estar entre 0 e 100.000."
                ];
            }
        }
        
        return ['valid' => true, 'message' => ''];
    }
    
    /**
     * Função pura: Cria objetos Employee a partir dos dados
     * @param array $rawData - Dados brutos
     * @return array - Array de objetos Employee
     */
    private function createEmployeesFromData(array $rawData): array {
        $validEmployees = PayrollCalculator::filterValidEmployees($rawData['employees']);
        
        // Usa array_map (função de ordem superior) para criar objetos
        return array_map(function($empData) {
            // Processa benefícios
            $benefits = [];
            if (!empty($empData['valeTransporte'])) {
                $benefits['vale_transporte'] = floatval($empData['valeTransporte']);
            }
            if (!empty($empData['planoSaude'])) {
                $benefits['plano_saude'] = floatval($empData['planoSaude']);
            }
            
            // Processa outros descontos
            $discounts = [];
            if (!empty($empData['outrosDescontos'])) {
                $discounts['outros'] = floatval($empData['outrosDescontos']);
            }
            
            // Retorna novo objeto Employee (imutável)
            return new Employee(
                $empData['name'],
                floatval($empData['grossSalary']),
                $empData['costCenter'] ?? 'Geral',
                $benefits,
                $discounts
            );
        }, $validEmployees);
    }
    
    /**
     * Renderiza a view
     * @param string $viewName - Nome da view
     * @param array $data - Dados para a view
     */
    public function render(string $viewName, array $data = []): void {
        extract($data);
        require_once "views/{$viewName}.php";
    }
}