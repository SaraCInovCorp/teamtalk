<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\InviteAccept;

//paginas publicas
Route::view('/', 'welcome')->name('home');
Route::view('/faq', 'faq')->name('faq');
Route::get('/invite/accept/{token}', function ($token) { return view('chat/invite-accept', ['token' => $token]);})->name('invite.accept');



//grupo autenticado via sanctum
Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified', ])->group(function () {
    Route::get('/dashboard', function () { return view('chat.chat-dashboard'); })->name('dashboard');
    
    Route::get('chat', fn() => view('chat.chat'))->name('chat');
    Route::get('chat/dashboard', fn() => view('chat.chat-dashboard'))->name('chat.dashboard');
    Route::get('chat/salas', fn() => view('chat.chat-rooms'))->name('chat.rooms');
    Route::get('chat/salas/{room}/config', function ($room) {
        return view('chat.room-settings', ['roomId' => $room]);
    })->name('chat.room.settings');
    Route::get('chat/contatos', fn() => view('chat.chat-contacts'))->name('chat.contacts');
    Route::get('chat/contatos/convidar', fn() => view('chat.invite-contact'))->name('chat.invite');
    Route::get('chat/admin', fn() => view('chat.admin-panel'))->middleware('can:admin')->name('chat.admin');
});
