<?php
/**
 * Model Responsável pela lógica de negócio e cálculos da folha de pagamento
 * @author Heron Zonta da Silva
 * @since 23/10/2025
 */

class FolhaPagamento {
    
    private $tabelaINSS;
    private $tabelaIR;
    private $tetoINSS;
    
    public function __construct() {
        $this->tabelaINSS = TABELA_INSS;
        $this->tabelaIR = TABELA_IR;
        $this->tetoINSS = TETO_INSS;
    }
    
    /**
     * Calcula o INSS usando o método progressivo por faixas
     * @param float $salarioBruto
     * @return float Valor do INSS a ser descontado
     */
    public function calcularINSS($salarioBruto) {
        $inss = 0;
        $salarioRestante = $salarioBruto;
        $faixaAnterior = 0;
        
        foreach ($this->tabelaINSS as $faixa) {
            if ($salarioRestante <= 0) break;
            
            $baseCalculo = min($salarioRestante, $faixa['limite'] - $faixaAnterior);
            $inss += $baseCalculo * $faixa['aliquota'];
            
            $salarioRestante -= $baseCalculo;
            $faixaAnterior = $faixa['limite'];
        }
        
        // Aplica o teto do INSS
        return min($inss, $this->tetoINSS);
    }
    
    /**
     * Calcula o Imposto de Renda usando o método progressivo por faixas
     * @param float $salarioBruto
     * @param float $inss Valor do INSS já calculado
     * @return float Valor do IR a ser descontado
     */
    public function calcularIR($salarioBruto, $inss) {
        $baseIR = $salarioBruto - $inss;
        
        foreach ($this->tabelaIR as $faixa) {
            if ($baseIR <= $faixa['limite']) {
                $ir = ($baseIR * $faixa['aliquota']) - $faixa['deducao'];
                return max(0, $ir);
            }
        }
        
        return 0;
    }
    
    /**
     * Calcula a folha de pagamento completa
     * @param float $salarioBruto
     * @param float $beneficios
     * @param float $outrosDescontos
     * @return array Resultado completo dos cálculos
     * @throws Exception Se houver erro de validação
     */
    public function calcularFolha($salarioBruto, $beneficios = 0, $outrosDescontos = 0) {
        // Validações
        if ($salarioBruto < 0) {
            throw new Exception("Salário bruto não pode ser negativo");
        }
        
        if ($beneficios < 0) {
            throw new Exception("Benefícios não podem ser negativos");
        }
        
        if ($outrosDescontos < 0) {
            throw new Exception("Outros descontos não podem ser negativos");
        }
        
        // Cálculos
        $inss = $this->calcularINSS($salarioBruto);
        $ir = $this->calcularIR($salarioBruto, $inss);
        
        $totalDescontos = $inss + $ir + $outrosDescontos;
        $salarioLiquido = $salarioBruto + $beneficios - $totalDescontos;
        
        // Invariante: salário líquido deve ser >= 0
        if ($salarioLiquido < 0) {
            throw new Exception("Salário líquido não pode ser negativo. Verifique os valores informados.");
        }
        
        return [
            'salarioBruto' => $salarioBruto,
            'beneficios' => $beneficios,
            'inss' => $inss,
            'ir' => $ir,
            'outrosDescontos' => $outrosDescontos,
            'totalDescontos' => $totalDescontos,
            'salarioLiquido' => $salarioLiquido
        ];
    }
    
    /**
     * Calcula totais agrupados por centro de custo
     * @param array $colaboradores Lista de colaboradores
     * @return array Totais por centro de custo
     */
    public function calcularPorCentroCusto($colaboradores) {
        $totaisPorCentro = [];
        
        foreach ($colaboradores as $colab) {
            $centro = $colab['centroCusto'];
            
            if (!isset($totaisPorCentro[$centro])) {
                $totaisPorCentro[$centro] = [
                    'totalBruto' => 0,
                    'totalLiquido' => 0,
                    'totalDescontos' => 0,
                    'quantidadeColaboradores' => 0
                ];
            }
            
            $resultado = $this->calcularFolha(
                $colab['salario'],
                $colab['beneficios'] ?? 0,
                $colab['outrosDescontos'] ?? 0
            );
            
            $totaisPorCentro[$centro]['totalBruto'] += $resultado['salarioBruto'];
            $totaisPorCentro[$centro]['totalLiquido'] += $resultado['salarioLiquido'];
            $totaisPorCentro[$centro]['totalDescontos'] += $resultado['totalDescontos'];
            $totaisPorCentro[$centro]['quantidadeColaboradores']++;
        }
        
        return $totaisPorCentro;
    }
}
?>