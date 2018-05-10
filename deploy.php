<?php

namespace Deployer;

require 'deploy/vendor/autoload.php';
require 'deploy/vendor/deployer/deployer/recipe/symfony.php';

set('ssh_type', 'native');

serverList('deploy/servers.yml');

set('writable_dirs', ['App/logs']);
set('shared_dirs', ['App/logs']);
set('shared_dirs', ['vendor']);

set('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

task('deploy:update_staging', function () {
    runLocally('cd ' . get('local_path'));
    runLocally('git stash');
    runLocally('git fetch');

    runLocally('git checkout develop');
    runLocally('git pull origin develop');
    runLocally('git checkout staging');
    runLocally('git pull origin staging');
    runLocally('git merge develop');
    runLocally('git push origin staging');
})->onlyForStage('staging');

task('deploy:run_migrations', function () {
    run('cd {{release_path}} && DB_HOST={{DB_HOST}} DB_NAME={{DB_NAME}} DB_USERNAME={{DB_USERNAME}} DB_PASSWORD={{DB_PASSWORD}} ant run-migrations');
})->desc('Run migrations');


task('deploy:seed_oauth_creds', function () {
    run('cd {{release_path}} && DB_HOST={{DB_HOST}} DB_NAME={{DB_NAME}} DB_USERNAME={{DB_USERNAME}} DB_PASSWORD={{DB_PASSWORD}} DEFAULT_CLIENT_ID={{DEFAULT_CLIENT_ID}} DEFAULT_CLIENT_SECRET={{DEFAULT_CLIENT_SECRET}} ant seed-database');
})->desc('Seed OAuth Credentials');

task('deploy:share_logs', function () {
    run('sudo chmod -R 777 {{deploy_path}}/shared/App/logs');
});

task('deploy:docs', function () {
    run('cd {{release_path}}/docs &&  bundle install && bundle exec middleman build --clean');
})->onlyForStage('staging');

/**
 * Upload env file
 */
task('deploy:upload_environments_file', function () {
    upload(get('local_path') . '/env/.env.{{APPLICATION_ENV}}', '{{release_path}}/env/.env');
});

/**
 * Upload env file
 */
task('deploy:upload_programs_config', function () {
    upload(get('local_path') . '/conf.d/programs.conf.{{APPLICATION_ENV}}', '{{release_path}}/conf.d/programs.conf');
});


task('release:tag_release', function () {
    writeln('Tagging Release... ');
    runLocally('cd ' . get('local_path'));
    runLocally('git stash');
    runLocally('git checkout production');

    $releaseVersion = get('RELEASE_VERSION');
    $releaseMessage = get('RELEASE_MESSAGE');

    runLocally('git tag -a ' . $releaseVersion . ' -m "' . $releaseMessage . '"');
    runLocally('git push --tags');
    runLocally('git checkout develop');
    writeln('Release Tagged Successfully');
})->onlyForStage('production');

task('deploy:update_production', function () {
    writeln('Updating Production Branch... ');
    runLocally('cd ' . get('local_path'));
    runLocally('git stash');
    runLocally('git fetch');
    runLocally('git checkout master');
    runLocally('git pull origin master');

    if (get('IS_KANBAN_DEPLOYMENT') != 1) {
        runLocally('git checkout staging');
        runLocally('git pull origin staging');
        runLocally('git checkout master');
        runLocally('git merge staging');
        runLocally('git push origin master');
    }

    runLocally('git checkout production');
    runLocally('git pull origin production');
    runLocally('git merge master');
    runLocally('git push origin production');
    writeln('Production Branch Updated Successfully');
})->onlyForStage('production');

task('webserver:restart', function () {
    run('sudo service php5.6-fpm restart');
    run('sudo service nginx restart');
});

/**
 * Start the beanstalkd queue
 */
task('restart_tasks', function () {
    run('sudo supervisorctl reread');
    run('sudo supervisorctl update');
    run('sudo supervisorctl restart all');
});


/**
 * Main task
 */
task('deploy', [
    'deploy:update_staging',
    'deploy:update_production',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:upload_programs_config',
    'deploy:upload_environments_file',
    'deploy:run_migrations',
    'deploy:seed_oauth_creds',
    'deploy:symlink',
    'deploy:writable',
    'cleanup',
    'webserver:restart',
    'restart_tasks',
    'release:tag_release'
])->desc('Deploy Project');


set('repository', get('REPOSITORY_URL'));

//slack tasks
task('slack:before_deploy', function () {
    postToSlack('Starting deploy on ' . get('server.name') . '...');
});

task('slack:after_deploy', function () {
    postToSlack('Deploy to ' . get('server.name') . ' done');
});

task('composer:token', function () {
    run('composer config -g github-oauth.github.com ' . get('GITHUB_TOKEN'));
});

function postToSlack($message)
{
    $slackHookUrl = get('SLACK_HOOK_URL');
    if (!empty($slackHookUrl)) {
        runLocally('curl -s -S -X POST --data-urlencode payload="{\"channel\": \"#' . get('SLACK_CHANNEL_NAME') .
            '\", \"username\": \"{{SLACK_BOT_USERNAME}}\", \"text\": \"' . $message . '\"}" ' . get('SLACK_HOOK_URL'));
    } else {
        write('Configure the SLACK_HOOK_URL to post to slack');
    }
}

before('deploy', 'slack:before_deploy');
after('deploy', 'slack:after_deploy');
before('deploy:vendors', 'composer:token');
