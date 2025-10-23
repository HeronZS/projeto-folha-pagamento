<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Folha de Pagamento</title>
    <link rel="stylesheet" href="views/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Sistema de Folha de Pagamento</h1>
        <p class="subtitle">Programação Funcional - Cálculo de INSS e IR por Faixas</p>

        <?php if (isset($result) && !$result['success']): ?>
            <div class="alert alert-error">
                <strong>Erro:</strong> <?= htmlspecialchars($result['error']) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($result) && $result['success']): ?>
            <div class="alert alert-success">
                <strong>Sucesso!</strong> Folha de pagamento processada com sucesso!
            </div>

            <div class="results-section">
                <h2>📊 Resultados da Folha de Pagamento</h2>

                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Centro de Custo</th>
                            <th>Salário Bruto</th>
                            <th>INSS</th>
                            <th>IR</th>
                            <th>Benefícios</th>
                            <th>Descontos</th>
                            <th>Salário Líquido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['payroll_results'] as $employee): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($employee['name']) ?></strong></td>
                                <td><?= htmlspecialchars($employee['cost_center']) ?></td>
                                <td>R$ <?= number_format($employee['gross_salary'], 2, ',', '.') ?></td>
                                <td class="value-negative">-R$ <?= number_format($employee['inss'], 2, ',', '.') ?></td>
                                <td class="value-negative">-R$ <?= number_format($employee['ir'], 2, ',', '.') ?></td>
                                <td class="value-positive">+R$ <?= number_format($employee['total_benefits'], 2, ',', '.') ?></td>
                                <td class="value-negative">-R$ <?= number_format($employee['total_discounts'], 2, ',', '.') ?></td>
                                <td class="value-positive"><strong>R$ <?= number_format($employee['net_salary'], 2, ',', '.') ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cost-center-section">
                    <h3>📈 Totais por Centro de Custo</h3>
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Centro de Custo</th>
                                <th>Funcionários</th>
                                <th>Total Bruto</th>
                                <th>Total INSS</th>
                                <th>Total IR</th>
                                <th>Total Descontos</th>
                                <th>Total Líquido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result['cost_center_totals'] as $cc): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($cc['cost_center']) ?></strong></td>
                                    <td><?= $cc['employee_count'] ?></td>
                                    <td>R$ <?= number_format($cc['total_gross'], 2, ',', '.') ?></td>
                                    <td class="value-negative">R$ <?= number_format($cc['total_inss'], 2, ',', '.') ?></td>
                                    <td class="value-negative">R$ <?= number_format($cc['total_ir'], 2, ',', '.') ?></td>
                                    <td class="value-negative">R$ <?= number_format($cc['total_discounts'], 2, ',', '.') ?></td>
                                    <td class="value-positive"><strong>R$ <?= number_format($cc['total_net'], 2, ',', '.') ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">
                    Novo Cálculo
                </button>
            </div>

        <?php else: ?>

            <form method="POST" action="index.php" id="payrollForm">
                <div id="employeesContainer">
                    <!-- Um bloco inicial para o primeiro funcionário (name index 0) -->
                    <div class="employee-section" data-employee-index="0">
                        <h3>👤 Funcionário #1</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name_0">Nome *</label>
                                <input type="text" id="name_0" name="employees[0][name]" required placeholder="Ex: João Silva">
                            </div>

                            <div class="form-group">
                                <label for="grossSalary_0">Salário Bruto (R$) *</label>
                                <input type="number" id="grossSalary_0" name="employees[0][grossSalary]" step="0.01" min="0" required placeholder="Ex: 3000.00">
                            </div>

                            <div class="form-group">
                                <label for="costCenter_0">Centro de Custo</label>
                                <input type="text" id="costCenter_0" name="employees[0][costCenter]" placeholder="Ex: Administrativo">
                            </div>

                            <div class="form-group">
                                <label for="valeTransporte_0">Vale Transporte (R$)</label>
                                <input type="number" id="valeTransporte_0" name="employees[0][valeTransporte]" step="0.01" min="0" placeholder="Ex: 150.00">
                            </div>

                            <div class="form-group">
                                <label for="planoSaude_0">Plano de Saúde (R$)</label>
                                <input type="number" id="planoSaude_0" name="employees[0][planoSaude]" step="0.01" min="0" placeholder="Ex: 200.00">
                            </div>

                            <div class="form-group">
                                <label for="outrosDescontos_0">Outros Descontos (R$)</label>
                                <input type="number" id="outrosDescontos_0" name="employees[0][outrosDescontos]" step="0.01" min="0" placeholder="Ex: 50.00">
                            </div>
                        </div>
                        <div style="margin-top:12px; text-align:right;">
                            <button type="button" class="btn btn-secondary remove-employee" style="display:none;">Remover</button>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" id="addEmployeeBtn" class="btn btn-primary">Adicionar Funcionário</button>
                    <button type="submit" class="btn btn-success">Processar Folha</button>
                </div>
            </form>

        <?php endif; ?>

        <div class="footer" role="contentinfo">
            <p>Desenvolvido por: Heron Zonta da Silva</p>
            <p>Regras: funções puras, imutabilidade, uso de map/filter/reduce no backend</p>
        </div>
    </div>

    <script>
        (function () {
            const container = document.getElementById('employeesContainer');
            const addBtn = document.getElementById('addEmployeeBtn');

            function createEmployeeBlock(index) {
                const wrapper = document.createElement('div');
                wrapper.className = 'employee-section';
                wrapper.setAttribute('data-employee-index', index);

                wrapper.innerHTML = `
                    <h3>👤 Funcionário #${index + 1}</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name_${index}">Nome *</label>
                            <input type="text" id="name_${index}" name="employees[${index}][name]" required placeholder="Ex: João Silva">
                        </div>

                        <div class="form-group">
                            <label for="grossSalary_${index}">Salário Bruto (R$) *</label>
                            <input type="number" id="grossSalary_${index}" name="employees[${index}][grossSalary]" step="0.01" min="0" required placeholder="Ex: 3000.00">
                        </div>

                        <div class="form-group">
                            <label for="costCenter_${index}">Centro de Custo</label>
                            <input type="text" id="costCenter_${index}" name="employees[${index}][costCenter]" placeholder="Ex: Administrativo">
                        </div>

                        <div class="form-group">
                            <label for="valeTransporte_${index}">Vale Transporte (R$)</label>
                            <input type="number" id="valeTransporte_${index}" name="employees[${index}][valeTransporte]" step="0.01" min="0" placeholder="Ex: 150.00">
                        </div>

                        <div class="form-group">
                            <label for="planoSaude_${index}">Plano de Saúde (R$)</label>
                            <input type="number" id="planoSaude_${index}" name="employees[${index}][planoSaude]" step="0.01" min="0" placeholder="Ex: 200.00">
                        </div>

                        <div class="form-group">
                            <label for="outrosDescontos_${index}">Outros Descontos (R$)</label>
                            <input type="number" id="outrosDescontos_${index}" name="employees[${index}][outrosDescontos]" step="0.01" min="0" placeholder="Ex: 50.00">
                        </div>
                    </div>
                    <div style="margin-top:12px; text-align:right;">
                        <button type="button" class="btn btn-secondary remove-employee">Remover</button>
                    </div>
                `;
                return wrapper;
            }

            function updateRemoveButtons() {
                const removeButtons = container.querySelectorAll('.remove-employee');
                removeButtons.forEach(btn => btn.style.display = container.children.length > 1 ? 'inline-block' : 'none');
                removeButtons.forEach(btn => {
                    btn.onclick = function () {
                        const section = this.closest('.employee-section');
                        if (section) section.remove();
                        reindexEmployeeBlocks();
                        updateRemoveButtons();
                    };
                });
            }

            function reindexEmployeeBlocks() {
                const blocks = container.querySelectorAll('.employee-section');
                blocks.forEach((block, idx) => {
                    block.setAttribute('data-employee-index', idx);
                    const h3 = block.querySelector('h3');
                    if (h3) h3.textContent = `👤 Funcionário #${idx + 1}`;
                    const inputs = block.querySelectorAll('input');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name') || '';
                        const id = input.getAttribute('id') || '';
                        const newName = name.replace(/employees\[\d+\]/, `employees[${idx}]`);
                        input.setAttribute('name', newName);
                        const newId = id.replace(/_\d+$/, `_${idx}`);
                        input.setAttribute('id', newId);
                    });
                });
            }

            addBtn.addEventListener('click', () => {
                const newIndex = container.children.length;
                const newBlock = createEmployeeBlock(newIndex);
                container.appendChild(newBlock);
                reindexEmployeeBlocks();
                updateRemoveButtons();
            });

            updateRemoveButtons();

            document.getElementById('payrollForm')?.addEventListener('submit', function (e) {
                const salaryInputs = this.querySelectorAll('input[name^="employees"][name$="[grossSalary]"]');
                for (const input of salaryInputs) {
                    if (input.value === '' || parseFloat(input.value) < 0) {
                        alert('Por favor informe salários válidos (números positivos).');
                        e.preventDefault();
                        return false;
                    }
                }
                return true;
            });
        })();
    </script>
</body>
</html>
