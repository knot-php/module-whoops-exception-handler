<?php
declare(strict_types=1);

namespace KnotPhp\Module\WhoopsExceptionHandler;

use Throwable;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleInterface;

use KnotPhp\Module\WhoopsExceptionHandler\Adapter\WhoopsExceptionHandlerAdapter;

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
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}