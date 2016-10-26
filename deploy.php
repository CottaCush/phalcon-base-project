<?php
/**
 * Author: Adeyemi Olaoye <yemexx1@gmail.com>
 */

require 'recipe/common.php';

serverList('deploy/servers.yml');

set('writable_dirs', ['app/logs']);
set('shared_dirs', ['app/logs']);

env('composer_options', 'install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');


task('release:tag_release', function () {

    $promptAnswer = null;

    while (!in_array(strtolower(trim($promptAnswer)), ['y', 'n', 'yes', 'no'])) {
        $promptAnswer = ask('Tag Release? (y/n)');
    }

    if (!in_array($promptAnswer, ['y', 'yes'])) {
        return;
    }

    writeln('Tagging Release... ');
    runLocally('cd ' . env('local_path'));
    runLocally('git stash');
    runLocally('git checkout master');
    runLocally('git pull origin master');
    runLocally('git fetch');
    $result = runLocally('git for-each-ref --format="%(tag)" --sort=-taggerdate  refs/tags');
    $currentTag = current($result->toArray());
    $releaseVersion = '';

    while (strlen(trim($releaseVersion)) == 0) {
        $releaseVersion = ask('Enter Release Version (Current: ' . $currentTag . '): ');
    }

    $releaseMessage = ask('Enter Release Message: ');

    runLocally('git tag -a ' . $releaseVersion . ' -m "' . $releaseMessage . '"');
    runLocally('git push --tags');
    runLocally('git checkout master');
    writeln('Release Tagged Successfully');
})->onlyForStage('production');

task('deploy:update_staging', function () {
    runLocally('cd ' . env('local_path'));
    runLocally('git stash');
    runLocally('git checkout master');
    runLocally('git pull origin master');
    runLocally('git checkout staging');
    runLocally('git pull origin staging');
    runLocally('git merge master');
    runLocally('git push origin staging');
    runLocally('git checkout master');
})->onlyForStage('staging');

task('deploy:update_production', function () {
    runLocally('cd ' . env('local_path'));
    runLocally('git stash');
    runLocally('git checkout master');
    runLocally('git pull origin master');
    runLocally('git checkout production');
    runLocally('git pull origin production');
    runLocally('git merge master');
    runLocally('git push origin production');
    runLocally('git checkout master');
})->onlyForStage('production');

task('deploy:run_migrations', function () {
    run('cd {{release_path}} && PHINX_DBHOST={{PHINX_DBHOST}} PHINX_DBUSER={{PHINX_DBUSER}} PHINX_DBPASS={{PHINX_DBPASS}} PHINX_DBNAME={{PHINX_DBNAME}} php vendor/bin/phinx migrate -e {{APPLICATION_ENV}}
');
})->desc('Run migrations');


task('deploy:seed_oauth_creds', function () {
    run('cd {{release_path}} && CLIENT_ID={{DEFAULT_OAUTH_CLIENT_ID}} CLIENT_SECRET={{DEFAULT_OAUTH_CLIENT_SECRET}} PHINX_DBHOST={{PHINX_DBHOST}} PHINX_DBUSER={{PHINX_DBUSER}} PHINX_DBPASS={{PHINX_DBPASS}} PHINX_DBNAME={{PHINX_DBNAME}} php vendor/bin/phinx seed:run -s OauthSeeder -e {{APPLICATION_ENV}}');
})->desc('Seed OAuth Credentials');

/**
 * Upload env file
 */
task('deploy:upload_environments_file', function () {
    upload(env('local_path') . '/env/.env.{{APPLICATION_ENV}}', '{{release_path}}/env/.env');
});

task('webserver:restart', function () {
    run('sudo service apache2 restart');
});


/**
 * Main task
 */
task('deploy', [
    'deploy:update_staging',
    'deploy:update_production',
    'release:tag_release',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:upload_environments_file',
    'deploy:run_migrations',
    'deploy:seed_oauth_creds',
    'deploy:symlink',
    'deploy:writable',
    'cleanup'
])->desc('Deploy Project');


set('repository', 'git@bitbucket.org:adeyemi/app.git');