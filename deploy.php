<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';
require 'contrib/npm.php';

set('application', 'personalsite');
set('repository', 'git@github.com:mrailton/personalsite.git');
set('php_fpm_version', '8.2');

host(getenv('HOST'))
    ->set('remote_user', 'personalsite')
    ->set('deploy_path', '~/www');

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:migrate',
    'npm:install',
    'npm:run:build',
    'deploy:publish',
    'artisan:queue:restart',
]);

task('npm:run:build', function () {
    cd('{{release_path}}');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
