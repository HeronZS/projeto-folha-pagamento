# Sistema de Folha de Pagamento - MVC (PHP)

Pequeno sistema didático em PHP seguindo arquitetura MVC, implementando:
- Funções puras / imutabilidade (no backend PHP).
- Uso de funções de ordem superior (`array_map`, `array_filter`, `array_reduce`).
- Validação básica de entradas.
- Cálculo por faixas de INSS e IR (tabelas de exemplo).

## Estrutura de pastas
/ (raiz)
|-- index.php
|-- controllers/
| -- PayrollController.php |-- models/ | |-- Employee.php | -- PayrollCalculator.php
-- views/ -- payroll_view.php

## Requisitos
- PHP 8.1+ (para `readonly` nas propriedades do `Employee`)
- Servidor local (XAMPP, MAMP, PHP built-in server, etc.)

## Como executar
1. Coloque a pasta do projeto no `www`/`htdocs` do seu servidor local, ou rode:
php -S localhost:8000

Copiar código
na raiz do projeto.
2. Acesse `http://localhost:8000/index.php` (ou conforme seu servidor).
3. Preencha os funcionários e clique em **Processar Folha**.

## Observações
- As tabelas de INSS e IR contidas em `models/PayrollCalculator.php` são exemplos para fins didáticos. Atualize valores conforme as tabelas oficiais se necessário.
- Todas as funções do `PayrollCalculator` são puras (retornam valores sem modificar estado global).
- A view contém pequeno JavaScript para adicionar/remover funcionários dinamicamente — isto apenas facilita a entrada, os cálculos são feitos no servidor em PHP.

## Autoria
Substitua os nomes abaixo:
- Desenvolvido por: Heron Zonta

## Licença
Uso educativo / não comercial.
