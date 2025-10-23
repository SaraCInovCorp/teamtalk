<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\InviteAccept;
use App\Livewire\RoomCreate;
use App\Livewire\ChatRooms;
use App\Livewire\ChatMessages;
use App\Livewire\RoomSettings;
use App\Livewire\ChatContacts;
use App\Livewire\AdminPanel;

// Páginas públicas
Route::view('/', 'welcome')->name('home');
Route::view('/faq', 'faq')->name('faq');
Route::get('/invite/accept/{token}', InviteAccept::class)->name('invite.accept');

// Grupo autenticado via Sanctum + Jetstream
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::get('/dashboard', fn() => view('chat.chat-dashboard'))->name('dashboard');
    
    Route::get('chat', fn() => view('chat.chat'))->name('chat');
    Route::get('chat/dashboard', fn() => view('chat.chat-dashboard'))->name('chat.dashboard');

    // Livewire Page Components
    Route::get('chat/salas', ChatRooms::class)->name('chat.rooms');
    Route::get('chat/salas/criar', RoomCreate::class)->name('chat.room.create');
    Route::get('chat/salas/{room}/config', RoomSettings::class)->name('chat.room.settings');
    Route::get('chat/salas/{room?}', ChatMessages::class)->name('chat.room');

    Route::get('/chat/privado/{recipient}', ChatMessages::class)->name('chat.messages.private');


    Route::get('chat/contatos', ChatContacts::class)->name('chat.contacts');
    Route::get('chat/contatos/convidar', fn() => view('chat.invite-contact'))->name('chat.invite');

    Route::get('chat/admin', AdminPanel::class)->middleware('can:admin')->name('chat.admin');

});
