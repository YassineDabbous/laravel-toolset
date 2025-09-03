<?php
namespace Yaseen\Toolset;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Yaseen\Toolset\Http\Actions\ActionHandler;
use Yaseen\Toolset\Http\Middleware\LangMiddleware;

class ToolsetServiceProvider extends ServiceProvider
{


    public function register()
    {
        app('router')->group(['prefix' => 'api/v1', 'middleware' => ['auth:sanctum']], function ($router) {
            $router->post('_action_', ActionHandler::class);
        });

        
        
    }





    public function boot(Router $router)
    {
        
        $router->middleware(LangMiddleware::class);
        
        // Model::shouldBeStrict(); // shouldBeStrict(! app()->isProduction());
        // Http::preventStrayRequests(); // Don’t let any requests go out. Good for testing

        $this->logQueries();
        
        // if ($this->app->runningInConsole()) {
        //     $this->offerPublishing();
        // }
    }
    



    public function logQueries(){
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

}
