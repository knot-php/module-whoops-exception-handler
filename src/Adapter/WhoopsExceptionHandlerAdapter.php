<?php
declare(strict_types=1);

namespace KnotPhp\Module\WhoopsExceptionHandler\Adapter;

use Throwable;
use Whoops\Run;

use KnotLib\Kernel\ExceptionHandler\ExceptionHandlerInterface;

final class WhoopsExceptionHandlerAdapter implements ExceptionHandlerInterface
{
    /** @var Run */
    private $whoops;

    /**
     * WhoopsExceptionHandlerAdapter constructor.
     *
     * @param Run $whoops
     */
    public function __construct(Run $whoops)
    {
        $this->whoops = $whoops;
    }

    /**
     * {@inheritDoc}
     */
    public function handleException(Throwable $e)
    {
        echo $this->whoops->handleException($e);
    }

}