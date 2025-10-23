<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\User;

class ExpirePrivateMessages extends Command
{
    protected $signature = 'messages:expire';
    protected $description = 'Marcar mensagens privadas como inativas se estiverem expiradas';
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('has_temporary_messages', true)
            ->whereNotNull('private_message_expire_days')->get();

        foreach ($users as $user) {
            $days = $user->private_message_expire_days;

            Message::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id);
            })->where('created_at', '<=', now()->subDays($days))
             ->where('is_active', true)
             ->update(['is_active' => false]);
        }

        $this->info('Mensagens expiradas atualizadas.');
    
    }
}
