<?php namespace App\Http\Middleware;

use Closure;
use Crypt;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;
use MsgException;

class VerifyCsrfToken extends Middleware
{

    /**
     * Handle an incoming request.
     * Note: This method overwrites Laravel's default handle() method.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws \Illuminate\Session\TokenMismatchException
     * @throws \Illuminate\Http\Exceptions\OriginMismatchException
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, function ($request) use ($next) {
            /*
             * Spam protection: Forms that have set a value for _created_at
             * are protected against mass submitting.
             * WARNING: Not sending the field will not trigger the verification!
             */
            if (! $this->isReading($request) && ($time = $request->input('_created_at'))) {
                $time = Crypt::decrypt($time);

                if (is_numeric($time)) {
                    $time = (int) $time;

                    if ($time <= time() - 3) {
                        return $next($request);
                    }
                }

                throw new MsgException(trans('app.spam_protection'));
            }

            return $next($request);
        });
    }
}
