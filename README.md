# TrabWeb1
## **Funcionalidades**

### **Sistema de Jogo**
- **Timer de 30 segundos** com contagem regressiva visual
- **Frases aleatórias** obtidas via API externa (quotable.io)
- **Sistema de pontuação** (10 pontos por letra correta)
- **Feedback visual** em tempo real (letras corretas/incorretas)
- **Histórico de partidas** com data e pontuação
- **Recorde pessoal** salvo automaticamente

### **Sistema de Usuários**
- **Cadastro seguro** com validação de dados
- **Login/Logout** com sessões PHP
- **Senhas criptografadas** (password_hash)
- **Validação de email** e confirmação
- **Proteção contra SQL Injection** (prepared statements)

### **Sistema de Ligas**
- **Criação de ligas** por usuários
- **Gerenciamento de ligas** (criar, editar, deletar)
- **Ranking por liga** com pontuação total
- **Visualização de membros** por liga

---

## **Tecnologias Utilizadas**

### **APIs Externas**
- **Quotable.io** - Geração de frases aleatórias

---

##  **Estrutura do Projeto**

```
TrabWeb1-main/
├── README.md
└── jogo/
    └── src/
        ├── assets/
        │   ├── css/           # Estilos CSS
        │   │   ├── cadastro.css
        │   │   ├── ger_ligas.css
        │   │   ├── hist.css
        │   │   ├── index.css
        │   │   ├── jogo.css
        │   │   ├── liga.css
        │   │   └── login.css
        │   └── js/            # Scripts JavaScript
        │       └── jogo.js    # Lógica principal do jogo
        ├── components/        # Componentes reutilizáveis
        │   └── header.php     # Header compartilhado
        ├── config/           # Configurações
        │   ├── auth.php      # Validações de autenticação
        │   ├── connection.php # Conexão com banco
        │   └── functions.php  # Funções utilitárias
        ├── models/           # Modelos de dados
        │   └── model.php
        └── pages/            # Páginas da aplicação
            ├── cadastro.php   # Página de cadastro
            ├── ger_ligas.php  # Gerenciamento de ligas
            ├── hist.php       # Histórico de partidas
            ├── index.php      # Menu principal
            ├── jogo.php       # Página do jogo
            ├── ligas.php      # Visualização de ligas
            ├── login.php      # Página de login
            └── logout.php     # Logout
```



### ** Mecânica do Jogo**
- **Duração:** 30 segundos por partida
- **Objetivo:** Digitar o máximo de letras corretas possível


### ** Sistema de Pontuação**
```javascript

Frase 1: "Hello world" (11 letras) = 110 pontos
Frase 2: "Programming" (11 letras) = 110 pontos  
Frase 3: "Code" (4 letras - incompleta) = 40 pontos
Total: 260 pontos
```

### ** Ranking e Histórico**
- Histórico pessoal salvo automaticamente
- Ranking por liga baseado na soma total de pontos
- Visualização do recorde pessoal

---

##  **Banco de Dados**

### ** Estrutura das Tabelas**

#### **users**
```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    clan_id INT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (clan_id) REFERENCES clans(clan_id)
);
```

#### **clans**
```sql
CREATE TABLE clans (
    clan_id INT AUTO_INCREMENT PRIMARY KEY,
    clan_name VARCHAR(100) NOT NULL,
    clan_password VARCHAR(100)
);
```

#### **historic**
```sql
CREATE TABLE historic (
    historic_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    points INT NOT NULL,
    date_match TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```
