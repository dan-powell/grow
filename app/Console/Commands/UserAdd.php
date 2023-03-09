<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Hash};

class UserAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {username} {password} {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newuser = [
            'email' => $this->argument('username'),
            'name' => $this->argument('username'),
            'password' => Hash::make($this->argument('password')),
        ];

        if ($this->option('overwrite') == false) {
            DB::table('users')->insert($newuser);
            $this->line('User "' . $this->argument('username') . '" added');
        } else {
            $builder = DB::table('users')->where('id', '=', $this->argument('username'));

            if ($builder->get()) {
                $builder->update($newuser);
                $this->line('User ID: ' . $this->argument('username') . ' overwritten');
            } else {
                $this->line('User ID: ' . $this->argument('username') . ' not found');
            }
        }
    }
}
