<?php

/*
 * Soluble Japha
 *
 * @link      https://github.com/belgattitude/soluble-japha
 * @copyright Copyright (c) 2013-2020 Vanvelthem Sébastien
 * @license   MIT License https://github.com/belgattitude/soluble-japha/blob/master/LICENSE.md
 */

namespace SolubleTest\Japha\Bridge;

use Soluble\Japha\Bridge\Adapter;
use Soluble\Japha\Interfaces;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-04 at 16:47:42.
 */
class AdapterSystemTest extends TestCase
{
    /**
     * @var string
     */
    protected $servlet_address;

    /**
     * @var Adapter
     */
    protected $ba;

    /**
     * @var Interfaces\JavaObject
     */
    protected $backupTz;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        \SolubleTestFactories::startJavaBridgeServer();

        $this->servlet_address = \SolubleTestFactories::getJavaBridgeServerAddress();

        $this->ba = new Adapter([
            'driver' => 'Pjb62',
            'servlet_address' => $this->servlet_address,
        ]);
        $this->backupTz = $this->ba->javaClass('java.util.TimeZone')->getDefault();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        if ($this->ba !== null) {
            $this->ba->javaClass('java.util.TimeZone')->setDefault($this->backupTz);
        }
    }

    public function testGetSystemTimeZoneId()
    {
        $tzId = $this->ba->getSystem()->getTimeZoneId();
        self::assertIsString($tzId);
    }

    public function testSetSystemTimeZoneId()
    {
        $this->ba->getSystem()->setTimeZoneId('Europe/London');
        $tzId = $this->ba->getSystem()->getTimeZoneId();
        self::assertEquals('Europe/London', $tzId);

        $this->ba->getSystem()->setTimeZoneId('CET');
        $tzId = $this->ba->getSystem()->getTimeZoneId();
        self::assertEquals('CET', $tzId);
    }

    public function testGetTimeZone()
    {
        $tz = $this->ba->getSystem()->getTimeZone();
        self::assertInstanceOf('Soluble\Japha\Util\TimeZone', $tz);
    }
}
