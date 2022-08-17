<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'grow.local');

// Project repository
set('repository', 'git@github.com:dan-powell/grow.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', [

]);

// Writable dirs by web server
// set('writable_mode', 'chown');
// set('http_user', 'http_user');

set('writable_dirs', []);

// Hosts
inventory('hosts.yml');

set('default_stage', 'production');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('composerconfig-prod', function () {
})->onStage('production');

before('deploy:vendors', 'composerconfig-prod');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:symlink', 'artisan:queue:restart');
