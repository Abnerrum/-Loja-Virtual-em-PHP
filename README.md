# 🛒 Loja Virtual em PHP

Projeto de uma loja virtual simples desenvolvida em PHP puro, com integração a banco de dados MySQL e sistema de carrinho utilizando sessões.

---

## 🚀 Funcionalidades

* Listagem de produtos
* Carrinho de compras (com quantidade)
* Adicionar / remover produtos
* Atualizar quantidade
* Cálculo automático do total
* Checkout simples
* Finalização de pedido

---

## 🛠️ Tecnologias Utilizadas

* PHP (puro)
* MySQL
* HTML5
* CSS3
* Bootstrap (interface)
* Sessões PHP

---

## 📁 Estrutura do Projeto

```
/loja
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

---

## ⚙️ Como Executar o Projeto

### 1. Instalar servidor local

Recomendado utilizar o XAMPP:
https://www.apachefriends.org/index.html

---

### 2. Clonar ou baixar o projeto

Coloque a pasta dentro de:

```
C:\xampp\htdocs\
```

---

### 3. Iniciar serviços

No painel do XAMPP:

* Iniciar Apache
* Iniciar MySQL

---

### 4. Criar banco de dados

Acesse:
http://localhost/phpmyadmin

Execute o script SQL:

```sql
CREATE DATABASE loja;

USE loja;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    preco DECIMAL(10,2)
);

INSERT INTO produtos (nome, preco) VALUES
('Notebook', 3500.00),
('Mouse Gamer', 150.00),
('Teclado Mecânico', 400.00);
```

---

### 5. Acessar o sistema

No navegador:

```
http://localhost/loja
```

---

## 📸 Demonstração

Interface simples com listagem de produtos e carrinho funcional.

---

## 🔐 Segurança

* Uso de PDO para conexão com banco
* Prepared Statements contra SQL Injection
* Uso básico de sessões

---

## 📌 Melhorias Futuras

* Sistema de login e cadastro
* Painel administrativo (CRUD de produtos)
* Upload de imagens
* Integração com gateway de pagamento
* Responsividade avançada
* Estrutura MVC

---

## 💳 Integração de Pagamento (Sugestão)

* Mercado Pago
* Stripe

---

## 📚 Aprendizados

Este projeto foi desenvolvido com foco em:

* Estruturação de aplicações PHP
* Manipulação de sessões
* Integração com banco de dados
* Lógica de carrinho de compras

---

## 👨‍💻 Autor

Desenvolvido por Abner Luiz

---

## 📄 Licença

Este projeto é livre para uso e aprendizado.

```
```
