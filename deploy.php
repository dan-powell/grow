<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'grow.dan-powell.uk');

// Project repository
set('repository', 'git@github.com:dan-powell/grow.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', [
]);

// Writable dirs by web server
set('writable_mode', 'chown');
set('writable_use_sudo', true);
set('writable_recursive', true);
set('http_user', 'www-data');

set('writable_dirs', [
    '{{release_or_current_path}}/storage'
]);

// Hosts
import('hosts.yml');

set('default_stage', 'production');

set('bin/composer', 'sudo docker exec -u $(id -u):$(id -g) -i -w {{release_or_current_path}} test_nginx composer');

set('bin/php', 'sudo docker exec -u $(id -u):$(id -g) -i -w {{release_or_current_path}} test_nginx php');

// Tasks

task('artisan:breadcrumbs:cache', function () {
    run('{{bin/php}} {{release_path}}/artisan breadcrumbs:cache');
});

before('artisan:route:cache', 'artisan:breadcrumbs:cache');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:symlink', 'artisan:queue:restart');

// Restart Horizon & purge rogue processes
before('deploy:publish', 'artisan:horizon:purge');
before('deploy:publish', 'artisan:horizon:terminate');

// Handle frontend assets
task('assets:deploy', function () {
    // Build frontend assets locally and push to server
    // NPM must be built from Lando
    $config = [];
    upload('public/build/', '{{ release_path }}/public/build/', $config);
});
before('deploy:shared', 'assets:deploy');



task('environment:upload', function () {
    upload('.env.production', '{{deploy_path}}/shared/.env', ['progress_bar' => false]);
    run('{{bin/php}} {{release_path}}/artisan config:cache');
});