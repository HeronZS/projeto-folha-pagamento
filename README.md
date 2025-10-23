# 💰 Sistema de Folha de Pagamento

Sistema desenvolvido em PHP com arquitetura MVC para cálculo automatizado de folha de pagamento, incluindo INSS e IR por faixas progressivas.

## 📋 Descrição do Projeto

Este sistema realiza o cálculo completo da folha de pagamento de colaboradores, aplicando as regras tributárias brasileiras vigentes (2024) para INSS e Imposto de Renda, além de permitir a inclusão de benefícios e outros descontos.

## 🎯 Funcionalidades

- ✅ Cálculo de INSS por faixas progressivas
- ✅ Cálculo de IR por faixas progressivas
- ✅ Aplicação de benefícios (vale alimentação, transporte, etc.)
- ✅ Aplicação de outros descontos (plano de saúde, empréstimos, etc.)
- ✅ Validação de entradas numéricas
- ✅ Respeito aos tetos e limites legais
- ✅ Verificação de invariantes (salário líquido ≥ 0)
- ✅ Interface intuitiva e responsiva

## 🏗️ Arquitetura MVC

```
projeto-folha-pagamento/
│
├── index.php                    # Ponto de entrada
│
├── config/
│   └── config.php              # Configurações e tabelas
│
├── models/
│   └── FolhaPagamento.php      # Lógica de negócio
│
├── controllers/
│   └── FolhaController.php     # Processamento
│
└── views/
    └── folha_view.php          # Interface HTML
```

### 📦 Responsabilidades das Camadas

**Model (FolhaPagamento.php)**
- Cálculos de INSS e IR
- Validações de regras de negócio
- Aplicação de invariantes

**Controller (FolhaController.php)**
- Processamento de requisições
- Validação de dados de entrada
- Tratamento de exceções

**View (folha_view.php)**
- Apresentação da interface
- Formulários de entrada
- Exibição de resultados

## 🚀 Como Executar

### Pré-requisitos
- PHP 7.4 ou superior
- Servidor web (Apache/Nginx) ou PHP Built-in Server

### Instalação

1. Clone ou baixe o projeto:
```bash
git clone [url-do-repositorio]
cd projeto-folha-pagamento
```

2. Execute com PHP Built-in Server:
```bash
php -S localhost:8000
```

3. Acesse no navegador:
```
http://localhost:8000
```

**OU**

Coloque a pasta no diretório do seu servidor local (htdocs/www) e acesse:
```
http://localhost/projeto-folha-pagamento/
```

## 📊 Tabelas Tributárias Utilizadas

### INSS 2024
| Faixa Salarial | Alíquota |
|---|---|
| Até R$ 1.412,00 | 7,5% |
| De R$ 1.412,01 até R$ 2.666,68 | 9% |
| De R$ 2.666,69 até R$ 4.000,03 | 12% |
| De R$ 4.000,04 até R$ 7.786,02 | 14% |

**Teto do INSS:** R$ 908,85

### IR 2024
| Base de Cálculo | Alíquota | Dedução |
|---|---|---|
| Até R$ 2.259,20 | Isento | - |
| De R$ 2.259,21 até R$ 2.826,65 | 7,5% | R$ 169,44 |
| De R$ 2.826,66 até R$ 3.751,05 | 15% | R$ 381,44 |
| De R$ 3.751,06 até R$ 4.664,68 | 22,5% | R$ 662,77 |
| Acima de R$ 4.664,68 | 27,5% | R$ 896,00 |

## 💡 Exemplo de Uso

### Entrada:
- Salário Bruto: R$ 5.000,00
- Benefícios: R$ 500,00
- Outros Descontos: R$ 200,00

### Saída:
- INSS: R$ 465,65
- IR: R$ 346,53
- Total de Descontos: R$ 1.012,18
- **Salário Líquido: R$ 4.487,82**

## 🔒 Regras de Negócio

### Validações Implementadas:
- Salário bruto não pode ser negativo
- Benefícios não podem ser negativos
- Descontos não podem ser negativos
- Salário líquido deve ser ≥ 0

### Invariantes:
- Total de descontos = INSS + IR + Outros descontos
- Salário líquido = Salário bruto + Benefícios - Total de descontos

## 🛠️ Tecnologias Utilizadas

- **PHP** 7.4+
- **HTML5**
- **CSS3**
- **Arquitetura MVC**

## 📝 Observações

- O cálculo do INSS utiliza o método progressivo (por faixas)
- O IR é calculado sobre a base (salário bruto - INSS)
- Sistema preparado para expansão (cálculo por centro de custo já implementado)
- Código bem documentado e comentado

## 👨‍💻 Desenvolvedor

Projeto desenvolvido como trabalho acadêmico para demonstração de conceitos de:
- Arquitetura MVC
- Programação Orientada a Objetos
- Validação de dados
- Regras de negócio
- Interface responsiva

## 📄 Licença

Este projeto é de uso acadêmico e educacional.

---

**Versão:** 1.0  
**Data:** Outubro 2025