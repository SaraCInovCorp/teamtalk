<p align="center">
  <a href="#">
    <img src="public/logo.png" width="400" alt="TeamTalk Logo">
  </a>
</p>

# TeamTalk

Sistema de chat e colaboração em tempo real construído com **Laravel 12**, oferecendo autenticação robusta, controle de papéis dinâmicos e interface moderna e responsiva.

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

- **users:** usuários do sistema, com os seguintes campos:
  - `name`, `email`, `password`
  - `role` (string: *admin* | *user*)
  - `avatar` (foto de usuário)
  - `profile_photo_path` (foto de perfil do Jetstream)
  - `bio` (biografia curta)
  - `status_message` (mensagem de status exibida no chat)
  - `is_active` (flag para indicar se o usuário está ativo no sistema)
  - `last_seen_at` (último momento online)
- **rooms:** salas de chat criadas, com avatar, nome e criador.
- **room_user:** tabela pivô relacionando usuários às salas, com o papel respectivo (membro ou admin).
- **messages:** registro de mensagens, podendo referenciar uma sala (mensagens públicas) ou destinatário (mensagens privadas).

### Relações importantes e fluxo

- Usuários têm papéis definidos diretamente na tabela `users` via campo `role`.
- Cada usuário pode participar de múltiplas salas via tabela intermediária `room_user`.
- Mensagens privadas e de grupo coexistem na tabela `messages`.
- Logs de atividades (implementados via **Spatie Activity Log**) registram eventos importantes como logins e envio de mensagens.

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

## Autenticação e Registro

O sistema usa **Laravel Jetstream com Livewire**, permitindo:
- Registro e login completo.
- Upload de **foto de perfil** (`profile_photo`) e **avatar** durante o cadastro.
- Campos customizados: *bio*, *status_message* e flag `is_active`.

As imagens são armazenadas em:
- `storage/app/public/avatars`
- `storage/app/public/profile-photos`

Para torná-las acessíveis publicamente:

```
php artisan storage:link
```

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

## Papéis e Permissões

- Papéis são definidos no campo `role` de `users`.
- Controle de acesso é feito por **Policies** e **Gates** padrão do Laravel.

Exemplo:

```
public function update(User $user, Room $room)
{
return $user->role === 'admin';
}
```

---

## Log de Atividades (Audit Trail)

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

## Frontend e UI

- TailwindCSS e DaisyUI prontos.
- Em `tailwind.config.js`:  
  Adicione o DaisyUI ao array de plugins.

```
plugins: [forms, typography, require('daisyui')],
```

Inclui também:
- **Componentes Blade personalizados** para botões, inputs, labels flutuantes e checkboxes.
- **Design responsivo** utilizando padrões utilitários do Tailwind.

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
//Se não estiver utilizando o Herd
php artisan reverb:start # Iniciar servidor websocket
npm run dev # Compilar assets
```

---

## Dicas  de Desenvolvimento

- Crie branches separadas para desenvolvimento:
  - `main` para produção.
  - `dev` para testes internos.
- Mantenha o layout base com Livewire e scripts de broadcasting ativos.
- Utilize componentes Blade reutilizáveis (~btn, input, checkbox, label~) para consistência visual e produtividade. 

---

© 2025 **TeamTalk** — Todos os direitos reservados.
