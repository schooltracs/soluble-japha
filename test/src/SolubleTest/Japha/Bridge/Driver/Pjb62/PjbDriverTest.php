<?php

/*
 * Soluble Japha
 *
 * @link      https://github.com/belgattitude/soluble-japha
 * @copyright Copyright (c) 2013-2017 Vanvelthem Sébastien
 * @license   MIT License https://github.com/belgattitude/soluble-japha/blob/master/LICENSE.md
 */

namespace SolubleTest\Japha\Bridge\Driver\Pjb62;

use Soluble\Japha\Bridge\Adapter;
use Soluble\Japha\Bridge\Driver\Pjb62\InternalJava;
use Soluble\Japha\Bridge\Driver\Pjb62\Pjb62Driver;
use Soluble\Japha\Bridge\Driver\Pjb62\PjbProxyClient;
use Soluble\Japha\Bridge\Exception\BrokenConnectionException;
use Soluble\Japha\Bridge\Exception\ClassNotFoundException;
use Soluble\Japha\Interfaces\JavaObject;
use Soluble\Japha\Bridge\Exception\InvalidArgumentException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-11-13 at 10:21:03.
 */
class PjbDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $servlet_address;

    /**
     * @var string
     */
    protected $options;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        \SolubleTestFactories::startJavaBridgeServer();
        $this->servlet_address = \SolubleTestFactories::getJavaBridgeServerAddress();
        $this->adapter = new Adapter([
            'driver' => 'Pjb62',
            'servlet_address' => $this->servlet_address,
        ]);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testConstructorNoLogger()
    {
        $driver = new Pjb62Driver([
            'servlet_address' => $this->servlet_address,
        ], $logger = null);
    }

    public function testConnect()
    {
        $driver = new Pjb62Driver([
            'servlet_address' => $this->servlet_address,
        ]);
        $driver->connect();
    }

    public function testInstanciate()
    {
        $driver = $this->adapter->getDriver();
        $driver->instanciate('java.lang.String');
    }

    public function testGetClient()
    {
        $client = $this->adapter->getDriver()->getClient();
        $this->assertInstanceOf(PjbProxyClient::class, $client);
    }

    public function testIsIntanceOf()
    {
        $string = $this->adapter->java('java.lang.String', 'hello');
        $bool = $this->adapter->getDriver()->isInstanceOf($string, 'java.lang.String');
        $this->assertTrue($bool);
    }

    public function testIsInstanceOfThrowsException1()
    {
        $this->setExpectedException(ClassNotFoundException::class);
        $string = $this->adapter->java('java.lang.String', 'hello');
        $bool = $this->adapter->getDriver()->isInstanceOf($string, 'java.invalid.Str');
    }

    public function testIsInstanceOfThrowsException2()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $string = $this->adapter->java('java.lang.String', 'hello');
        $bool = $this->adapter->getDriver()->isInstanceOf($string, []);
    }

    public function testSetFileEncoding()
    {
        $driver = $this->adapter->getDriver();

        $encoding = 'ASCII';
        $driver->setFileEncoding($encoding);
        $encoding = (string) $driver->getConnectionOptions()->getEncoding();
        $this->assertEquals('ASCII', $encoding);

        $encoding = 'UTF-8';
        $driver->setFileEncoding($encoding);
        $encoding = (string) $driver->getConnectionOptions()->getEncoding();
        $this->assertEquals('UTF-8', $encoding);
    }

    public function testJavaContext()
    {
        $context = $this->adapter->getDriver()->getContext();
        $this->assertInstanceOf(JavaObject::class, $context);
        $this->assertInstanceOf(InternalJava::class, $context);

        $fqdn = $this->adapter->getClassName($context);
        $supported = [
          // Before 6.2.11 phpjavabridge version
          'servletPrevious' => 'php.java.servlet.HttpContext',
          // FROM 6.2.11 phpjavabridge version
          'servletCurrent' => 'io.soluble.pjb.servlet.HttpContext',
          'standalone' => 'php.java.bridge.http.Context',
        ];

        $this->assertTrue(in_array($fqdn, $supported));
    }

    public function testGetJavaBridgeHeader()
    {
        $headersToTest = [
          'HTTP_OVERRIDE_HOST' => 'cool',
          'HTTP_HEADER_HOST' => 'cool'
        ];

        $this->assertEquals('cool', Pjb62Driver::getJavaBridgeHeader('OVERRIDE_HOST', $headersToTest));
        $this->assertEquals('cool', Pjb62Driver::getJavaBridgeHeader('HTTP_OVERRIDE_HOST', $headersToTest));
        $this->assertEquals('cool', Pjb62Driver::getJavaBridgeHeader('HTTP_HEADER_HOST', $headersToTest));
        $this->assertEquals('', Pjb62Driver::getJavaBridgeHeader('NOTHING', $headersToTest));
    }

    public function testInstanciateThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->instanciate('java.lang.String');
    }

    public function testGetContextThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->getContext();
    }

    public function testInvokeThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->invoke(null, 'getContext');
    }

    public function testJavaSessionThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->getJavaSession();
    }

    public function testGetClassNameThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->getJavaSession();
    }

    public function testGetJavaClassThrowsBrokenConnectionException()
    {
        $this->expectException(BrokenConnectionException::class);
        $driver = $this->adapter->getDriver();
        PjbProxyClient::unregisterInstance();
        $driver->getJavaClass('java.lang.String');
    }
}
