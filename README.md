# Bitline Angola — Site + Blog + Painel Administrativo

Projeto completo em PHP puro (PDO/MySQL) para a Bitline Angola: site institucional, blog dinâmico e painel administrativo para gerir artigos e mensagens de contacto.

## 1. Requisitos

- PHP 8.0+ (com extensão PDO MySQL ativa)
- MySQL / MariaDB
- XAMPP, WAMP, Laragon ou similar para correr localmente

## 2. Instalação (VS Code + XAMPP/Laragon)

1. Copia a pasta `bitline-angola/` para `htdocs/` (XAMPP) ou `www/` (Laragon).
2. Cria a base de dados importando o ficheiro **`database/bitline.sql`**:
   - Via phpMyAdmin: cria uma base de dados vazia (ou deixa o script criar `bitline_angola`) e importa o ficheiro.
   - Via terminal: `mysql -u root -p < database/bitline.sql`
3. Abre **`config/database.php`** e confirma as credenciais (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`). Por omissão está configurado para o utilizador `root` sem password (padrão do XAMPP/Laragon).
4. Acede ao site em: `http://localhost/bitline-angola/index.php`

### Deploy no Vercel

Este projeto inclui `vercel.json` (runtime `vercel-php`) para correr no Vercel. Como o Vercel não tem disco persistente nem MySQL próprio, define estas variáveis de ambiente no painel do projeto (Settings → Environment Variables) apontando para uma base de dados MySQL externa:

`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `MODO_DEBUG=false`, `SITE_URL`

Nunca commitar os valores reais destas variáveis — configura-as apenas no painel do Vercel.

## 3. Acesso ao Painel Administrativo

URL: `http://localhost/bitline-angola/admin/login.php`

- **Email:** `admin@bitlineangola.co.ao`
- **Password:** definida apenas no ambiente local/produção (não incluída aqui por segurança — não commitar passwords em texto simples).

⚠️ **Se ainda usas a password original de instalação, muda-a assim que possível.** Para gerar uma nova password segura, corre em PHP:

```php
echo password_hash('a-tua-nova-password', PASSWORD_BCRYPT);
```

Copia o resultado e atualiza o campo `password` do utilizador na tabela `admins` (via phpMyAdmin).

## 4. O que já está pronto

- **Site institucional** (`index.php`) com todas as secções originais melhoradas: hero, serviços, sobre, diferenciais, processo, setores e contacto — agora com menu mobile funcional e totalmente responsivo.
- **Blog dinâmico** (`blog.php` + `artigo.php`): listagem com pesquisa e paginação, página individual com contagem de visualizações, tempo de leitura, partilha social e artigos relacionados.
- **Contacto** (`contacto.php`): formulário completo (nome, email, telefone, assunto, mensagem) gravado na base de dados, com proteção honeypot anti-bot.
- **Painel administrativo** (`admin/`): login seguro (password com hash bcrypt), dashboard com estatísticas, CRUD completo de artigos (criar, editar, eliminar, upload de imagem, rascunho/publicado, destaque) e gestão de mensagens de contacto (marcar como lido / eliminar).
- **Contactos oficiais** já configurados em `config/database.php`:
  - Unitel: **929 380 158**
  - Africell: **952 250 142**

## 5. Estrutura de pastas

```
bitline-angola/
├─ index.php / blog.php / artigo.php / contacto.php
├─ config/database.php        → ligação PDO + configurações gerais
├─ includes/header.php, footer.php, funcoes.php
├─ admin/                     → painel administrativo protegido por login
├─ actions/                   → processamento de formulários (contacto, artigos)
├─ css/estilo.css             → estilo completo (site + admin)
├─ js/app.js                  → interações do site (menu, reveal, AJAX)
├─ uploads/blog/              → imagens de capa dos artigos
└─ database/bitline.sql       → script de criação da base de dados
```

## 6. Notas de segurança para produção

- Muda a password do admin padrão.
- Define `SITE_URL` em `config/database.php` para o domínio real.
- Ativa HTTPS e considera adicionar `session.cookie_secure = 1` no `php.ini`.
- Faz backups regulares da base de dados e da pasta `uploads/`.

### Já incluído no código

- **Proteção contra SQL Injection**: todas as queries usam PDO com parâmetros preparados.
- **Passwords com hash bcrypt** (nunca em texto simples).
- **Proteção CSRF**: todos os formulários que alteram dados (contacto, artigos, login, ações de admin) verificam um token de sessão antes de processar.
- **Limite de tentativas de login**: 5 tentativas falhadas por IP bloqueiam o acesso por 10 minutos (tabela `login_tentativas`, criada automaticamente ao importar `database/bitline.sql`).
- **Cabeçalhos de segurança HTTP** (`X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy`, etc.), definidos em `config/database.php`.
- **Cookies de sessão reforçados** (`HttpOnly`, `SameSite=Lax`, e `Secure` automático quando o site está em HTTPS).

Se já tinhas a base de dados importada antes desta atualização, volta a correr `database/bitline.sql` — usa `CREATE TABLE IF NOT EXISTS`, por isso não apaga os teus dados, só acrescenta a tabela `login_tentativas` em falta.
