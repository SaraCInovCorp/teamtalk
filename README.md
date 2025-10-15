<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# TeamTalk

Sistema de chat e colaboração em tempo real construído com Laravel 12, utilizando autenticação robusta, controle de permissões e interface moderna.

## Stack Utilizada

- Laravel 12+
- Jetstream (Livewire)
- Livewire
- Broadcasting com Reverb
- Tailwind CSS + DaisyUI
- Spatie Activity Log (log de atividades de usuários)
- Pest PHP (framework de testes elegante para PHP)

---

## Estrutura do Banco de Dados

### Tabelas principais

- **users:** usuários do sistema, com campos de avatar, nome, email, estado, biografia, role (definido como string: 'admin' ou 'user') e mais.
- **rooms:** salas de chat criadas, com avatar, nome e criador.
- **room_user:** tabela pivô ligando usuários às salas, com papel em cada sala (membro, admin).
- **messages:** mensagens enviadas, relacionando sala (nullable para mensagens privadas), remetente, receptor (opcional para mensagens diretas), conteúdo, anexo, status de leitura e timestamps.

### Relações importantes e fluxo

- Usuários têm o papel (role) diretamente no campo role da tabela users (ex: 'admin', 'user').
- Usuários podem pertencer a várias salas (room_user), com controles de papel.
- Mensagens podem ser públicas (em salas) ou privadas (entre usuários).
- Log de atividades registra eventos importantes (logins, mensagens, ações administrativas).

---

## Instalação

### 1. Clonar o projeto

```
git clone git@github-inovcorp:SaraCInovCorp/teamtalk.git
cd teamtalk

```

### 2. Instalar dependências PHP

```
composer install
```


### 3. Instalar dependências Node.js

```
npm install
npm run dev
```


### 4. Configurar variáveis de ambiente

```
cp .env.example .env
```

**Editar .env para dados do banco e broadcasting**

```
php artisan key:generate
```


### 5. Rodar migrations

```
php artisan migrate
```

---

## Autenticação

Usa Jetstream com Livewire, oferecendo registro, login, gestão de perfil e funcionalidades modernas.

---

## Broadcasting (Reverb)

Para chat em tempo real/outras features:

```
php artisan install:broadcasting --reverb
```

**Confirme a instalação dos pacotes JS ao ser perguntado (yes)**

```
npm install
npm run dev
php artisan reverb:start
```


No `.env`:

```
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=...
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
```

---

## Permissões (Roles)

**Gestão de papéis sem Spatie Permission**:  
- O sistema usa o campo `role` na tabela `users` para identificar se o usuário é admin, user, guest etc.
- Controle de acesso e políticas são definidos diretamente em controllers, policies ou middlewares do Laravel:

```
// Exemplo de method policy sem Spatie
public function update(User $user, Room $room)
{
return $user->role === 'admin';
}
```

- Basta utilizar os métodos auxiliares no Model User, como `isAdmin()` ou via `Gate`.

---


## Log de Atividades

Instale e publique migrations do Spatie Activity Log:

```
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

Se quiser customizar a config:

```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
```

---

## Frontend

- TailwindCSS e DaisyUI prontos.
- Em `tailwind.config.js`:  
  Adicione o DaisyUI ao array de plugins.

```
plugins: [forms, typography, require('daisyui')],
```

---

## Testes automatizados com Pest

Este projeto utiliza **Pest** para toda a suite de testes, proporcionando sintaxe mais expressiva e execução simplificada.

### Rodar os testes

```
php artisan test
```

ou

```
./vendor/bin/pest
```


### Como instalar e configurar o Pest (caso venha de um fork sem o Pest)

```
composer remove phpunit/phpunit
composer require pestphp/pest --dev --with-all-dependencies
./vendor/bin/pest --init
```


### Como converter testes PHPUnit existentes para Pest

```
composer require pestphp/pest-plugin-drift --dev
./vendor/bin/pest --drift
```

O comando acima converte seus testes de PHPUnit para a sintaxe Pest automaticamente.

---

## Comandos Úteis

```
php artisan serve # Servidor local
php artisan reverb:start # Iniciar servidor websocket
npm run dev # Compilar assets
```

---

## Dicas

- Separe development em branches: main (produção), dev (testes).
- Tenha Livewire e scripts de broadcasting no seu layout Blade.

---


