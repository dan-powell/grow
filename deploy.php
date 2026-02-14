<?php

namespace Deployer;

use Symfony\Component\Console\Input\InputOption;

require 'recipe/laravel.php';

// Project name
set('application', 'grow.autumnrain.co.uk');

// Project repository
set('repository', 'git@github.com:dan-powell/grow.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
set('writable_mode', 'chmod');
set('writable_chmod_mode', 775);
set('writable_use_sudo', true);
set('writable_recursive', true);
set('http_user', 'www-data');
set('http_group', 'www-data');

set('writable_dirs', [
    '{{release_or_current_path}}/storage'
]);

// Hosts
import('hosts.yml');

set('default_stage', 'production');

function getDockerRunCommand($cmd) {
    $composeFile = get('docker_path') . '/docker-compose.yml';
    return sprintf(
        'sudo /usr/bin/docker compose -f %s run --rm -u $(id -u):$(id -g) -w {{release_or_current_path}} deploy %s',
        $composeFile,
        $cmd
    );
}

option('fresh', null, InputOption::VALUE_NONE, 'Fresh deployment: pushes local .env to remote');

task('deploy:check_fresh', function () {
    if (input()->getOption('fresh')) {
        writeln('<info>Fresh flag detected: Pushing .env file...</info>');
        invoke('env:push');
    }
});

after('deploy:shared', 'deploy:check_fresh');

// Set the binaries to use the helper function
set('bin/composer', function () {
    return getDockerRunCommand('composer');
});

set('bin/php', function () {
    return getDockerRunCommand('php');
});

// Tasks

task('deploy:fix_storage_permissions', function () {
    run('sudo chgrp -R www-data {{deploy_path}}/shared/storage');
    run('sudo chmod -R g+s {{deploy_path}}/shared/storage');
});

after('deploy:writable', 'deploy:fix_storage_permissions');

task('artisan:breadcrumbs:cache', function () {
    run('{{bin/php}} {{release_path}}/artisan breadcrumbs:cache');
});

task('artisan:octane:install', function () {
    run('{{bin/php}} {{release_path}}/artisan octane:install');
});

task('artisan:octane:reload', function () {
    run('{{bin/php}} {{release_path}}/artisan octane:reload');
});


before('artisan:route:cache', 'artisan:breadcrumbs:cache');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:symlink', 'artisan:queue:restart');

// Restart Horizon & purge rogue processes
before('deploy:publish', 'artisan:horizon:purge');
before('deploy:publish', 'artisan:horizon:terminate');
before('deploy:publish', 'artisan:octane:install');
before('deploy:publish', 'artisan:octane:reload');


// Handle frontend assets
task('assets:deploy', function () {
    // Build frontend assets locally and push to server
    // NPM must be built from Lando
    $config = [];
    upload('public/build/', '{{ release_path }}/public/build/', $config);
});
before('deploy:shared', 'assets:deploy');

task('env:pull', function () {
    download('{{deploy_path}}/shared/.env', '.env.production', ['progress_bar' => false]);
});

task('env:push', function () {
    upload('.env.production', '{{deploy_path}}/shared/.env', ['progress_bar' => false]);
});

// Add a standalone task to run composer install with dev dependencies
task('composer:install:dev', function () {
    run('{{bin/composer}} install --prefer-dist --optimize-autoloader');
});
