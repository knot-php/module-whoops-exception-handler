<?php
declare(strict_types=1);

namespace KnotPhp\Module\WhoopsExceptionHandler;

use Throwable;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentModule;

use KnotPhp\Module\WhoopsExceptionHandler\Adapter\WhoopsExceptionHandlerAdapter;

final class WhoopsExceptionHandlerModule extends ComponentModule
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [
            Components::EVENTSTREAM,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return Components::EX_HANDLER;
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