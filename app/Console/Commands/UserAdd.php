<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Description('Add a new user')]
#[Signature('user:add {username} {password} {--overwrite}')]
class UserAdd extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newuser = [
            'id' => Str::ulid(),
            'email' => $this->argument('username'),
            'name' => $this->argument('username'),
            'password' => Hash::make($this->argument('password')),
        ];

        if ($this->option('overwrite') == false) {
            DB::table('users')->insert($newuser);
            $this->line('User "'.$this->argument('username').'" added');
        } else {
            $builder = DB::table('users')->where('id', '=', $this->argument('username'));

            if ($builder->get()) {
                $builder->update($newuser);
                $this->line('User ID: '.$this->argument('username').' overwritten');
            } else {
                $this->line('User ID: '.$this->argument('username').' not found');
            }
        }
    }
}
