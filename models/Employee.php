<?php

/**
 * Classe Employee - Representa um funcionário de forma imutável
 * Todas as propriedades são readonly para garantir imutabilidade
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */
class Employee {
    public readonly string $name;
    public readonly float $grossSalary;
    public readonly string $costCenter;
    public readonly array $benefits; // ['vale_transporte' => valor, 'plano_saude' => valor]
    public readonly array $otherDiscounts; // outros descontos além de INSS/IR
    
    public function __construct(
        string $name,
        float $grossSalary,
        string $costCenter,
        array $benefits = [],
        array $otherDiscounts = []
    ) {
        $this->name = $name;
        $this->grossSalary = $grossSalary;
        $this->costCenter = $costCenter;
        $this->benefits = $benefits;
        $this->otherDiscounts = $otherDiscounts;
    }
    
    /**
     * Retorna uma nova instância com dados atualizados (imutabilidade)
     */
    public function withGrossSalary(float $newSalary): Employee {
        return new Employee(
            $this->name,
            $newSalary,
            $this->costCenter,
            $this->benefits,
            $this->otherDiscounts
        );
    }
}