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
// set('writable_mode', 'chown');
// set('http_user', 'http_user');

set('writable_dirs', []);

// Hosts
import('hosts.yml');

set('default_stage', 'production');

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
