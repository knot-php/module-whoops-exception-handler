<?php
declare(strict_types=1);

namespace knotphp\module\whoopsexceptionhandler\test\adapter;

use PHPUnit\Framework\TestCase;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

use knotlib\exception\runtime\InvalidArgumentException;

use knotphp\module\whoopsexceptionhandler\adapter\WhoopsExceptionHandlerAdapter;

final class WhoopsExceptionHandlerAdapterTest extends TestCase
{
    public function testHandleException()
    {
        $whoops = new Run;
        $whoops->allowQuit(false);
        $page = new PrettyPageHandler();
        $page->handleUnconditionally(true);
        $whoops->appendHandler($page);
        $adapter = new WhoopsExceptionHandlerAdapter($whoops);


        $e = new InvalidArgumentException(0,'arg');

        ob_start();
        $adapter->handleException($e);
        $content = ob_get_clean();

        $this->assertNotEmpty($content);

    }

}