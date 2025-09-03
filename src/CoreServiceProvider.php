<?php
namespace Ysn\SuperCore;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Ysn\SuperCore\Console\InstallCommand;
use Ysn\SuperCore\Http\Actions\ActionHandler;
use Ysn\SuperCore\Http\Middleware\LangMiddleware;
use Ysn\SuperCore\Services\Sms\SmsalaSmsSender;
use Ysn\SuperCore\Services\Sms\TwilioSmsSender;
use Ysn\SuperCore\Services\Sms\VonageSmsSender;
use Ysn\SuperCore\Casts\Spatial\MyGeoFactory;
use Ysn\SuperCore\Contracts\SmsSender;
use Ysn\SuperCore\Models\TenantModel;
use Ysn\SuperCore\Providers\TenancyEventServiceProvider;
use Ysn\SuperCore\Types\ModelType;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Illuminate\Contracts\Http\Kernel;
class CoreServiceProvider extends ServiceProvider
{


    public function register()
    {
        $this->app->register(TenancyEventServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'qarya');
        $this->mergeConfigFrom(__DIR__.'/../config/settings.php', 'settings');
        $this->mergeConfigFrom(__DIR__.'/../config/media-library.php', 'media-library');

        $this->registerDatabaseConfig();
        $this->registerStorageConfig();

        app('router')->group(['prefix' => 'api/v1', 'middleware' => ['auth:sanctum']], function ($router) {
            $router->post('_action_', ActionHandler::class);
        });

        $this->app->resolving(ResolvesTenants::class, function (ResolvesTenants $resolver) {
            $resolver->addModel(TenantModel::class);
            return $resolver;
        });

        
        // $this->app->singleton('wkb', function ($app) {
        //     //dump('new wkb instance');
        //     $factory = new MyGeoFactory();
        //     $parser = new \GeoIO\WKB\Parser\Parser($factory);
        //     return $parser;
        // });
        // $this->app->singleton(SettingsManager::class, fn() => new SettingsLocalManager());
        $this->app->singleton(SmsSender::class, function ($app) {
            return new SmsalaSmsSender();
        });
        
    }





    public function boot(Kernel $kernel)
    {
        
        $kernel->pushMiddleware(LangMiddleware::class);
        
        // Model::shouldBeStrict(); // shouldBeStrict(! app()->isProduction());
        // Http::preventStrayRequests(); // Don’t let any requests go out. Good for testing

        $this->logQueries();
        
        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerCommands();
        }
    }

    

    public function offerPublishing()
    {
        $this->publishes([
            __DIR__.'/../config/config.php'         => config_path('qarya.php'),
            __DIR__.'/../config/settings.php'         => config_path('settings.php'),
            __DIR__.'/../config/media-library.php'   => config_path('media-library.php'),
        ], 'qarya-config');

    }

    protected function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }



    public function logQueries(){
        //Log::alert(url()->full());
        //Log::info(request()->getClientIp());
            //dd(env('APP_DEBUG'));
        if(true) { // env('APP_DEBUG')
            DB::afterCommit(function() {
                File::ensureDirectoryExists(storage_path('logs/sql'));
                File::append(
                    storage_path('logs/sql/queries-'.date('d-M-Y').'.log'),
                    PHP_EOL . '--------------------------' . PHP_EOL
               );
            });
            // DB::whenQueryingForLongerThan(2000, function (Connection $connection) {
            //     // Log a warning if we spend more than a total of 2000ms querying.
            //     Log::warning("Database queries exceeded 2 seconds on {$connection->getName()}");
            // });
            DB::listen(function(\Illuminate\Database\Events\QueryExecuted $query) {
                File::ensureDirectoryExists(storage_path('logs/sql'));
                File::append(
                    storage_path('logs/sql/queries-'.date('d-M-Y').'.log'),
                    PHP_EOL .
                    'Time: '.date('H:i:s'). PHP_EOL .
                    'Speed: '.$query->time . 'ms'. PHP_EOL .
                    'Query: ' . $query->sql . PHP_EOL .
                    'Bindings: ' . '['.implode(',', $query->bindings).']' . PHP_EOL
               );
            });
        }
    }




 



    protected function registerDatabaseConfig()
    {
        foreach (config("qarya.connections", []) as $key => $value) {
            config([
                "database.connections.$key" => array_merge(
                    [
                        'driver' => env('DB_CONNECTION'), //'mysql'
                        'host' => env('DB_HOST'),
                        'database' => $value['database'],
                        'username' => $value['username'],
                        'password' => $value['password'],
                        'charset'   => 'utf8mb4', // for emoticons
                        'collation' => 'utf8mb4_unicode_ci', // for emoticons
                        'options' => extension_loaded('pdo_mysql') ? array_filter([
                            \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \'+00:00\'' // for datetime problem https://stackoverflow.com/questions/60584021/1292-incorrect-datetime-value-for-column-updated-at
                        ]) : [],
                    ], config("database.connections.$key", [])
                ),
            ]);
        }
    }


    protected function registerStorageConfig()
    {
        foreach (config("qarya.disks", []) as $key => $value) {
            config([
                "filesystems.disks.$key"       => $value,
            ]);
        }
    }
}
