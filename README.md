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

## Sistema de Contatos, Convites e Filtros

O TeamTalk possui um sistema avançado de contatos e convites, com os seguintes destaques:

- Convites para contato via e-mail, funcionando tanto para usuários já cadastrados quanto para novos e-mails externos.
- Quando um novo usuário se registra:
  - Todos os convites pendentes enviados para o e-mail dele são automaticamente associados à nova conta.
  - Convites aparecem na lista de "Convites Recebidos" após login, permitindo aceitar ou recusar.
  - O fluxo funciona tanto via link de convite (`invite_token`) quanto por registro normal.
- Agrupamento dinâmico dos contatos em:
  - **Contatos Aceitos** (amizades confirmadas recíprocas)
  - **Convites Recebidos** (aguardam ação do usuário)
  - **Convites Pendentes** (enviados e não respondidos)
- Aceitar, recusar e cancelar convites diretamente da interface, com atualização em tempo real das listas e feedback visual imediato.
- Filtro alfabético: índice de letras que filtra apenas nomes/e-mails que começam com a letra escolhida.
- Pesquisa por texto abrangente (nome ou e-mail), ativada por botão, sem conflito com filtro de letra.
- Sempre exibe a "outra pessoa" no relacionamento, nunca o próprio usuário.
- Mensagem informativa para todas as ações no painel de contatos.

---

---

## Funcionalidades do chat

### Sistema de Salas (Rooms)

O TeamTalk inclui um sistema completo de gerenciamento de salas de chat para colaboração em grupo:

- Usuários podem criar salas com nome e avatar personalizado.
- Cada sala tem um criador (usuário administrador da sala) e pode ter múltiplos membros.
- Relação muitos-para-muitos entre usuários e salas registradas na tabela `room_user`.
- Distinção de privilégios entre membros e administradores da sala, definindo papéis e permissões.
- Controle de acesso rigoroso com validação para impedir acesso de usuários bloqueados.
- Mensagens em salas são públicas aos membros da sala, com suporte a anexos e emojis.
- Implementação robusta para gerenciamento de entrada/saída dos usuários às salas, incluindo ações administrativas (promover, remover).
- Atualização dinâmica das mensagens em tempo real via broadcasting e Livewire.
- Permissões são configuradas para permitir/negаr ações como enviar mensagens, adicionar anexos, alterar configurações da sala e mais.
- Rotas dedicadas para criação, configuração e acesso direto às salas.
- Salas são visíveis apenas a usuários participantes ou administradores, com status ativo para controle.
- Logs de atividade são mantidos para auditoria e histórico das ações dentro das salas.


### Chat Privado e Mensagens em Tempo Real

O TeamTalk oferece um sistema robusto de chat privado, incluindo:

- Mensagens privadas entre usuários (usando o campo `recipient_id` em `messages`).
- Suporte a anexos em mensagens privadas e de grupo.
- Histórico de mensagens organizado, ordenação pela última mensagem (funciona em SQLite, MySQL e outros bancos).
- Sistema automatizado para remoção/expiração de mensagens temporárias conforme preferências do usuário (flag e dias configuráveis em `users`).
- Bloqueio de contatos com mantimento de histórico, sem recarregar contatos ocultos.
- Exibição e ocultação de conversas recentes sem perder histórico, com contato oculto armazenado em tabela específica (`hidden_private_chats`).
- Cada mensagem suporta textos, arquivos e emojis.

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
- **contacts:** relacionamentos de convites, aceitos e pendentes entre usuários e convites por e-mail.

### Relações importantes e fluxo

- Usuários têm papéis definidos no campo `role`.
- Cada usuário pode participar de múltiplas salas.
- Mensagens privadas e de grupo coexistem.
- Logs de atividades via **Spatie Activity Log** registram eventos importantes.
- Sistema de contatos gerenciado por Livewire, com separação entre convites recebidos, aceitos e pendentes.

### Outras migrates importantes:

- A tabela `hidden_private_chats` armazena contatos ocultos do usuário.
- Os campos de flags em `users`, como `has_temporary_messages` e `private_message_expire_days`.
- Os campos adicionais em `rooms`, como `description`, `allow_attachment`, etc.

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

- Laravel Jetstream com Livewire.
- Upload de foto de perfil (profile_photo).
- Campos customizados: bio e status_message.
- Convites via e-mail:
  - Registro com link de convite (/invite/accept/{token}) associa o token automaticamente.
  - Convites pendentes para o e-mail são vinculados ao usuário após registro.
- Imagens de perfil armazenadas em storage/app/public/profile-photos.
- Tornar acessíveis publicamente:

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
- Adicione DaisyUI ao array de plugins no tailwind.config.js.
- Inclui componentes Blade personalizados para botões, inputs, labels e checkboxes.
- Design responsivo com utilitários do Tailwind.

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
- Utilize componentes Blade reutilizáveis para consistência visual e produtividade.
- Explore a documentação do componente de contatos para avançar filtros, botões e abordagem de UX atualizada.

---

© 2025 **TeamTalk** — Todos os direitos reservados.
