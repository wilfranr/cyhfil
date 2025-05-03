<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class CheckFilamentAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        Filament::serving(function () {
            Filament::registerScriptData([
                'isAuthenticated' => Auth::check(),
            ]);
        });

dd(config('database.default'), config('database.connections.mysql'));
        return $next($request);
    }
}
