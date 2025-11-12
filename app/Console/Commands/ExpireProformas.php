<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireProformas extends Command
{
    protected $signature = 'proformas:expire';
    protected $description = 'Marca las proformas expiradas como is_expired = true';

    public function handle()
    {
        $expired = DB::table('proformas')
            ->where('is_expired', false)
            ->where('expiration_date', '<=', now())
            ->update(['is_expired' => true, 'updated_at' => now()]);

        $this->info("{$expired} proformas marcadas como expiradas.");
        
        return Command::SUCCESS;
    }
}