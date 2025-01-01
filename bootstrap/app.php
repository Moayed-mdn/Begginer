    <?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PharmacyMiddleware;
use App\Http\Middleware\WarehouseOwnerMiddleware;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
            $middleware->alias([
                "warehouseMiddleware"=>WarehouseOwnerMiddleware::class, 
                "pharmacyMiddleware"=>PharmacyMiddleware::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(Throwable $exceptions, Request $request){
            if($exceptions instanceof Illuminate\Database\UniqueConstraintViolationException)  {
                return response()->json(["error"=>"Duplicate Entery"],400);// custom validatoin in 
            }
        } );
    })->create();
