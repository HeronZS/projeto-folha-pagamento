<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SISTEMA_NOME; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .resultado {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        
        .resultado h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .linha-resultado {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .linha-resultado:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #667eea;
        }
        
        .erro {
            background: #ff4444;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
            color: #1976d2;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 <?php echo SISTEMA_NOME; ?></h1>
        <p class="subtitle">Cálculo de INSS e IR por faixas progressivas - v<?php echo SISTEMA_VERSAO; ?></p>
        
        <?php if ($dados['erro']): ?>
            <div class="erro">
                ⚠️ <?php echo htmlspecialchars($dados['erro']); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="salario">Salário Bruto (R$) *</label>
                <input 
                    type="text" 
                    id="salario" 
                    name="salario" 
                    placeholder="Ex: 5000.00"
                    value="<?php echo isset($_POST['salario']) ? htmlspecialchars($_POST['salario']) : ''; ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="beneficios">Benefícios (Vale alimentação, transporte, etc.) (R$)</label>
                <input 
                    type="text" 
                    id="beneficios" 
                    name="beneficios" 
                    placeholder="Ex: 500.00"
                    value="<?php echo isset($_POST['beneficios']) ? htmlspecialchars($_POST['beneficios']) : '0'; ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="outros_descontos">Outros Descontos (Plano de saúde, empréstimos, etc.) (R$)</label>
                <input 
                    type="text" 
                    id="outros_descontos" 
                    name="outros_descontos" 
                    placeholder="Ex: 200.00"
                    value="<?php echo isset($_POST['outros_descontos']) ? htmlspecialchars($_POST['outros_descontos']) : '0'; ?>"
                >
            </div>
            
            <button type="submit">Calcular Folha de Pagamento</button>
        </form>
        
        <?php if ($dados['resultado']): ?>
            <div class="resultado">
                <h2>📊 Resultado do Cálculo</h2>
                
                <div class="linha-resultado">
                    <span>Salário Bruto:</span>
                    <span>R$ <?php echo number_format($dados['resultado']['salarioBruto'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>Benefícios:</span>
                    <span style="color: green;">+ R$ <?php echo number_format($dados['resultado']['beneficios'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>INSS (por faixa):</span>
                    <span style="color: red;">- R$ <?php echo number_format($dados['resultado']['inss'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>IR (por faixa):</span>
                    <span style="color: red;">- R$ <?php echo number_format($dados['resultado']['ir'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>Outros Descontos:</span>
                    <span style="color: red;">- R$ <?php echo number_format($dados['resultado']['outrosDescontos'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>Total de Descontos:</span>
                    <span style="color: red;">- R$ <?php echo number_format($dados['resultado']['totalDescontos'], 2, ',', '.'); ?></span>
                </div>
                
                <div class="linha-resultado">
                    <span>💵 SALÁRIO LÍQUIDO:</span>
                    <span>R$ <?php echo number_format($dados['resultado']['salarioLiquido'], 2, ',', '.'); ?></span>
                </div>
            </div>
            
            <div class="info">
                ℹ️ <strong>Informações:</strong><br>
                • INSS calculado por faixas progressivas (teto: R$ <?php echo number_format(TETO_INSS, 2, ',', '.'); ?>)<br>
                • IR calculado após dedução do INSS<br>
                • Invariante respeitada: Salário líquido ≥ 0<br>
                • Validação: Total descontos = INSS + IR + Outros
            </div>
        <?php endif; ?>
        
        <div class="footer">
            Desenvolvido com MVC em PHP | Arquitetura: Model-View-Controller
        </div>
    </div>
</body>
</html>