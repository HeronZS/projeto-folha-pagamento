<?php

/**
 * Classe PayrollCalculator - Implementa funções puras para cálculos
 * Todas as funções são estáticas e não modificam estado externo
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */
class PayrollCalculator {
    
    // Tabela INSS 2024 (valores de exemplo)
    private const INSS_TABLE = [
        ['limit' => 1412.00, 'rate' => 0.075],
        ['limit' => 2666.68, 'rate' => 0.09],
        ['limit' => 4000.03, 'rate' => 0.12],
        ['limit' => 7786.02, 'rate' => 0.14],
    ];
    
    // Tabela IR 2024 (valores de exemplo)
    private const IR_TABLE = [
        ['limit' => 2259.20, 'rate' => 0.0, 'deduction' => 0],
        ['limit' => 2826.65, 'rate' => 0.075, 'deduction' => 169.44],
        ['limit' => 3751.05, 'rate' => 0.15, 'deduction' => 381.44],
        ['limit' => 4664.68, 'rate' => 0.225, 'deduction' => 662.77],
        ['limit' => PHP_FLOAT_MAX, 'rate' => 0.275, 'deduction' => 896.00],
    ];
    
    /**
     * Função pura: Valida se um valor é numérico e positivo
     * @param mixed $value - Valor a ser validado
     * @return bool - True se válido, false caso contrário
     */
    public static function isValidPositiveNumber($value): bool {
        return is_numeric($value) && floatval($value) >= 0;
    }
    
    /**
     * Função pura: Valida se um salário está dentro dos limites permitidos
     * @param float $salary - Salário a ser validado
     * @return bool - True se válido
     */
    public static function isValidSalary(float $salary): bool {
        return $salary >= 0 && $salary <= 100000; // Limite máximo arbitrário
    }
    
    /**
     * Função pura: Calcula INSS por faixa progressiva
     * @param float $grossSalary - Salário bruto
     * @return float - Valor do INSS calculado
     */
    public static function calculateINSS(float $grossSalary): float {
        $inss = 0.0;
        $previousLimit = 0.0;
        
        foreach (self::INSS_TABLE as $bracket) {
            if ($grossSalary <= $previousLimit) {
                break;
            }
            
            $taxableAmount = min($grossSalary, $bracket['limit']) - $previousLimit;
            $inss += $taxableAmount * $bracket['rate'];
            $previousLimit = $bracket['limit'];
        }
        
        return round($inss, 2);
    }
    
    /**
     * Função pura: Calcula IR por faixa progressiva
     * @param float $taxableBase - Base de cálculo (salário - INSS)
     * @return float - Valor do IR calculado
     */
    public static function calculateIR(float $taxableBase): float {
        $ir = 0.0;
        
        foreach (self::IR_TABLE as $bracket) {
            if ($taxableBase <= $bracket['limit']) {
                $ir = ($taxableBase * $bracket['rate']) - $bracket['deduction'];
                break;
            }
        }
        
        return max(0, round($ir, 2)); // IR não pode ser negativo
    }
    
    /**
     * Função pura: Calcula o total de benefícios
     * @param array $benefits - Array associativo de benefícios
     * @return float - Total de benefícios
     */
    public static function calculateTotalBenefits(array $benefits): float {
        // Usando array_reduce (função de ordem superior)
        return array_reduce(
            $benefits,
            fn($carry, $value) => $carry + floatval($value),
            0.0
        );
    }
    
    /**
     * Função pura: Calcula o total de outros descontos
     * @param array $discounts - Array de descontos
     * @return float - Total de descontos
     */
    public static function calculateTotalDiscounts(array $discounts): float {
        // Usando array_reduce (função de ordem superior)
        return array_reduce(
            $discounts,
            fn($carry, $value) => $carry + floatval($value),
            0.0
        );
    }
    
    /**
     * Função pura: Calcula o salário líquido completo
     * @param Employee $employee - Objeto funcionário (imutável)
     * @return array - Array com todos os cálculos
     */
    public static function calculateNetSalary(Employee $employee): array {
        $grossSalary = $employee->grossSalary;
        
        // Cálculo do INSS
        $inss = self::calculateINSS($grossSalary);
        
        // Base de cálculo do IR (salário - INSS)
        $irBase = $grossSalary - $inss;
        
        // Cálculo do IR
        $ir = self::calculateIR($irBase);
        
        // Total de benefícios (são adicionados ao salário)
        $totalBenefits = self::calculateTotalBenefits($employee->benefits);
        
        // Total de outros descontos
        $totalOtherDiscounts = self::calculateTotalDiscounts($employee->otherDiscounts);
        
        // Total de descontos
        $totalDiscounts = $inss + $ir + $totalOtherDiscounts;
        
        // Salário líquido (garantindo que não seja negativo - invariante)
        $netSalary = max(0, $grossSalary + $totalBenefits - $totalDiscounts);
        
        // Retorna novo array imutável com todos os valores
        return [
            'name' => $employee->name,
            'cost_center' => $employee->costCenter,
            'gross_salary' => round($grossSalary, 2),
            'inss' => $inss,
            'ir' => $ir,
            'total_benefits' => round($totalBenefits, 2),
            'total_other_discounts' => round($totalOtherDiscounts, 2),
            'total_discounts' => round($totalDiscounts, 2),
            'net_salary' => round($netSalary, 2),
            'benefits_detail' => $employee->benefits,
            'discounts_detail' => $employee->otherDiscounts
        ];
    }
    
    /**
     * Função de ordem superior: Processa múltiplos funcionários usando map
     * @param array $employees - Array de objetos Employee
     * @return array - Array com cálculos de todos os funcionários
     */
    public static function processMultipleEmployees(array $employees): array {
        // Usando array_map (função de ordem superior)
        return array_map(
            fn(Employee $employee) => self::calculateNetSalary($employee),
            $employees
        );
    }
    
    /**
     * Função pura: Agrupa totais por centro de custo
     * @param array $payrollResults - Resultado dos cálculos
     * @return array - Totais agrupados por centro de custo
     */
    public static function groupByCostCenter(array $payrollResults): array {
        $grouped = [];
        
        foreach ($payrollResults as $result) {
            $cc = $result['cost_center'];
            
            if (!isset($grouped[$cc])) {
                $grouped[$cc] = [
                    'cost_center' => $cc,
                    'total_gross' => 0,
                    'total_net' => 0,
                    'total_inss' => 0,
                    'total_ir' => 0,
                    'total_discounts' => 0,
                    'employee_count' => 0
                ];
            }
            
            $grouped[$cc]['total_gross'] += $result['gross_salary'];
            $grouped[$cc]['total_net'] += $result['net_salary'];
            $grouped[$cc]['total_inss'] += $result['inss'];
            $grouped[$cc]['total_ir'] += $result['ir'];
            $grouped[$cc]['total_discounts'] += $result['total_discounts'];
            $grouped[$cc]['employee_count']++;
        }
        
        return array_values($grouped);
    }
    
    /**
     * Função de ordem superior: Filtra funcionários válidos
     * @param array $employeesData - Dados brutos dos funcionários
     * @return array - Apenas funcionários com dados válidos
     */
    public static function filterValidEmployees(array $employeesData): array {
        // Usando array_filter (função de ordem superior)
        return array_filter(
            $employeesData,
            fn($data) => self::isValidPositiveNumber($data['grossSalary'] ?? 0) 
                && self::isValidSalary(floatval($data['grossSalary'] ?? 0))
                && !empty($data['name'])
        );
    }
}