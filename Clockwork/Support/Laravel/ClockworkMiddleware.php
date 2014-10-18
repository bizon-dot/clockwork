<?php namespace Clockwork\Support\Laravel;

use Clockwork\Clockwork;

use Closure;
use Exception;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Foundation\Application;

class ClockworkMiddleware implements Middleware
{
	/**
	 * The Laravel Application
	 *
	 * @var Application
	 */
	protected $app;

	/**
	 * Create a new middleware instance.
	 *
	 * @param  Application  $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$this->app['config']->set('clockwork::config.middleware', true);

		try {
			$response = $next($request);
		} catch (Exception $e) {
			$response = $this->app['exception']->handleException($e);
		}

		return $this->app['clockwork.support']->process($request, $response);
	}
}