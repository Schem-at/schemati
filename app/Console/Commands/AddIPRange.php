<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WhiteListedIp;

class AddIPRange extends Command
{
    protected $signature = 'ip-range:add';

    protected $description = 'Add an IP to the whitelist';

    public function handle()
    {
        $ended = false;
        while (!$ended) {
            $ip = $this->ask('Enter the IP to add');
            WhiteListedIp::create(['ip' => $ip]);
            $this->info('IP added successfully: ' . $ip);
            $ended = !$this->confirm('Do you want to add another IP?');
        }
    }
}
