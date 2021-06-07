<?php
declare(strict_types=1);

namespace knotphp\module\whoopsexceptionhandler;

use Throwable;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\module\ModuleInterface;

use KnotPhp\Module\WhoopsExceptionHandler\adapter\WhoopsExceptionHandlerAdapter;

final class WhoopsExceptionHandlerModule implements ModuleInterface
{
    /**
     * Declare dependency on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [];
    }

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponentTypes() : array
    {
        return [
            ComponentTypes::EVENTSTREAM,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::EX_HANDLER;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws ModuleInstallationException
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $whoops = new Run();

            $whoops->appendHandler(new PrettyPageHandler());

            $app->addExceptionHandler(new WhoopsExceptionHandlerAdapter($whoops));

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::EX_HANDLER_ADDED, $whoops);
        }
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }
}