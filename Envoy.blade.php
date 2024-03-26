@setup
require __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
try {
    $dotenv->load();
    $dotenv->required(['DEPLOY_SERVER', 'DEPLOY_REPOSITORY', 'DEPLOY_PATH'])->notEmpty();
} catch ( Exception $e )  {
    echo $e->getMessage();
}

$db_host_server = getenv('DB_HOST');
$db_pass_server = getenv('DB_PASS_SERVER');
$db_user_server = getenv('DB_USER_SERVER');
$bd_name_server = getenv('DB_NAME_SERVER');

$db_host = getenv('DB_HOST');
$db_pass = getenv('DB_PASSWORD');
$db_user = getenv('DB_USERNAME');
$bd_name = getenv('DB_DATABASE');



$site_name = 'integra-api.dmeat.cl';
$ssh_user = 'forge';
$server_ip = '104.248.52.120';
$server_base_path = '/home/forge/' . $site_name;
$sync_folder =  '/public/storage/'; // Themosis | Based on the base path of the project

$ssh_connection = $ssh_user.'@'.$server_ip;
@endsetup

@servers(['server' => [$ssh_connection], 'local' => 'localhost'])

@task('setup', ['on' => 'local'])
echo "<info>Installing composer dependencies...</info>";
composer install --prefer-dist
echo "<info>Installing NPM dependencies...</info>";
npm install
echo "<info>Compiling assets...</info>";
npm run watch
@endtask

@story('sync')
db:backup
db:update_local
db:delete_dump_server
db:delete_dump_local
pull_images
@endstory

@story('sync_database')
db:backup
db:update_local
db:delete_dump_server
db:delete_dump_local
@endstory

@task('show_env', ['on' => 'server'])
cat {{ $server_base_path }}/.env
@endtask

@task('db:backup', ['on' => 'server'])
cd {{ $server_base_path }}

export DB_HOST={{ $db_host_server }}
export DB_PASS={{ $db_pass_server }}
export DB_USER={{ $db_user_server }}
export DB_NAME={{ $bd_name_server }}

mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -c -e --default-character-set=utf8 --single-transaction --skip-set-charset --add-drop-database $DB_NAME | gzip -c > {{ $site_name }}.sql.gz
echo "Database dumped on server"
@endtask

@task('db:update_local', ['on' => 'local'])
if [ -f {{ $site_name }}.sql ]; then
    echo "File {{ $site_name }}.sql deleted"
    rm {{ $site_name }}.sql
fi

if [ -f {{ $site_name }}.sql.gz ]; then
    echo "File {{ $site_name }}.sql.gz deleted"
    rm {{ $site_name }}.sql.gz
fi

echo "Downloading database backup from server..."
rsync -avzP {{ $ssh_connection }}:{{ $server_base_path }}/{{ $site_name }}.sql.gz ./
echo "Database dump downloaded";
gzip -d {{ $site_name }}.sql.gz

echo "Importing backup in local database..."

echo "<info>midb</info>"
echo "<info>$DB_NAME</info>"

export DB_HOST={{ $db_host }}
export DB_PASS={{ $db_pass }}
export DB_USER={{ $db_user }}
export DB_NAME={{ $bd_name }}

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < {{ $site_name }}.sql --binary-mode
echo "<info>Local database updated</info>"
@endtask

@task('db:delete_dump_server', ['on' => 'server'])
cd {{ $server_base_path }}
if [ -f {{ $site_name }}.sql.gz ]; then
    rm {{ $site_name }}.sql.gz
fi
echo "Database dump deleted"
@endtask


@task('db:delete_dump_local', ['on' => 'local'])
if [ -f {{ $site_name }}.sql ]; then
    rm {{ $site_name }}.sql
fi
echo "Database dump deleted from local"
@endtask

@task('pull_images', ['on' => 'local'])
echo "Syncing images..."
rsync -avzP --exclude=bfi_thumb --exclude=cache {{ $ssh_connection }}:{{ $server_base_path }}{{ $sync_folder }} .{{ $sync_folder }}
echo "Images Synced"
@endtask