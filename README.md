# Loja Virtual em PHP

Projeto de e-commerce desenvolvido em PHP puro para praticar integração com MySQL, sessões, organização de regras de negócio e fluxo de carrinho de compras.

## Funcionalidades

- Listagem de produtos;
- Adição e remoção de itens do carrinho;
- Atualização de quantidades;
- Cálculo automático do total;
- Checkout e finalização simples do pedido.

## Tecnologias

- PHP;
- MySQL e PDO;
- HTML5 e CSS3;
- Bootstrap;
- Sessões PHP.

## Estrutura principal

```text
loja/
├── db.php
├── functions.php
├── index.php
├── carrinho.php
├── atualizar.php
├── remover.php
├── checkout.php
├── finalizar.php
└── style.css
```

## Como executar

1. Instale um ambiente local com Apache, PHP e MySQL, como XAMPP;
2. Clone este repositório dentro de `C:\xampp\htdocs\`;
3. Inicie o Apache e o MySQL;
4. Crie o banco e a tabela de produtos usando o exemplo abaixo;
5. Acesse `http://localhost/loja`.

```sql
CREATE DATABASE loja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loja;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    preco DECIMAL(10,2) NOT NULL
);

INSERT INTO produtos (nome, preco) VALUES
('Notebook', 3500.00),
('Mouse Gamer', 150.00),
('Teclado Mecânico', 400.00);
```

## Segurança

O projeto utiliza PDO e consultas preparadas para reduzir riscos de SQL Injection. Em uma aplicação de produção, ainda seriam necessários autenticação, autorização, validação completa de entradas, proteção CSRF, gestão segura de segredos e integração real com pagamentos.

## Próximas melhorias

- Cadastro e autenticação de usuários;
- Painel administrativo para produtos;
- Registro persistente de pedidos;
- Upload de imagens;
- Testes automatizados;
- Organização em arquitetura MVC.

## Autor

Desenvolvido por [Abner Luiz](https://github.com/Abnerrum).
