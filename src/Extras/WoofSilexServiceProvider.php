<?php
/**
 * WoofSilexServiceProvider.php
 *
 * Quick-n-dirty Silex service provider
 *
 * @package   Squinones\Woof\Extras
 * @author    Samantha Quiñones <samantha@tembies.com>
 * @copyright 2014 Samantha Quiñones
 * @license   http://opensource.org/licenses/MIT
 */

namespace Squinones\Woof\Extras;


use Silex\Application;
use Silex\ServiceProviderInterface;
use Squinones\Woof\Metric;
use Squinones\Woof\Socket;
use Squinones\Woof\Woof;

/**
 * Class WoofSilexServiceProvider
 *
 * @package Squinones\Woof\Extras
 */
class WoofSilexServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app["woof"] = $app->share(function () use ($app) {
            $woof = new Woof($app["woof.hostname"], $app["woof.port"]);
            $woof->setSocket($app["woof.socket"]);
            return $woof;
        });

        $app["woof.hostname"] = function () {
            return "localhost";
        };

        $app["woof.port"] = function () {
            return 8125;
        };

        $app["woof.socket"] = function () {
            return new Socket();
        };

        $app["woof.metric"] = function ($name, $value, $type, array $tags = [], $sampleRate = 1.0) {
            return new Metric($name, $value, $type, $tags, $sampleRate);
        };
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }
}
