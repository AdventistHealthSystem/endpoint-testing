<?php

namespace EndpointTesting\Tests\Log;

use EndpointTesting\Log\File;
use EndpointTesting\Log\Exception;

class FileTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $path = 'some path';
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->disableOriginalConstructor()
            ->setMethods(['setPath', 'setFileAdapter'])
            ->getMock();
        $sut->expects($this->once())
            ->method('setPath')
            ->with($this->equalTo($path));

        $result = $sut->__construct($path);
        $this->assertNull($result);
    }

    public function testSetPath()
    {
        $path = __FILE__;
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->disableOriginalConstructor()
            ->setMethods(['validatePath', 'setFileAdapter'])
            ->getMock();

        $sut->expects($this->once())
            ->method('validatePath')
            ->with($this->equalTo($path));

        $result = $sut->setPath($path);
        $this->assertEquals($sut, $result);

        $property = new \ReflectionProperty('\EndpointTesting\Log\File', 'path');
        $property->setAccessible(true);
        $result = $property->getValue($sut);

        $this->assertEquals($path, $result);
    }

    public function testGetPath()
    {
        $expected = 'some path';
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['validatePath', 'setFileAdapter'])
            ->getMock();

        $property = new \ReflectionProperty('\EndpointTesting\Log\File', 'path');
        $property->setAccessible(true);
        $temp = $property->setValue($sut, $expected);

        $result = $sut->getPath();
        $this->assertEquals($expected, $result);
    }

    public function testGetLines()
    {
        $path = __FILE__;
        $expected = file($path);
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['getPath', 'setFileAdapter'])
            ->getMock();

        $sut->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue($path));

        $result = $sut->getLines();
        $this->assertEquals($expected, $result);
    }

    /**
     * Testse the EndpointTesting\Log\File::validatePath method
     * @param  boolean $isValid
     * @dataProvider provideValidatePath
     */
    public function testValidatePath($isValid = true)
    {
        $path = __FILE__;
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->disableOriginalConstructor()
            ->setMethods(['isValidPath'])
            ->getMock();

        $sut->expects($this->once())
            ->method('isValidPath')
            ->with($this->equalTo($path))
            ->will($this->returnValue($isValid));

        if (! $isValid) {
            $this->expectException('\EndpointTesting\Log\Exception');
        }

        $result = $sut->validatePath($path);
        $this->assertEquals($sut, $result);
    }

    public function provideValidatePath()
    {
        return [
            [true],
            [false],
        ];
    }

    /**
     * Tests the EndpointTesting\Log\File::isValidPath method
     * @dataProvider provideIsValidPath
     */
    public function testIsValidPath($expected, $path = '')
    {
        $sut = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['validatePath', 'setFileAdapter'])
            ->getMock();

        $result = $sut->isValidPath($path);
        $this->assertEquals($expected, $result);
    }

    public function provideIsValidPath()
    {
        return [
            'has a path, should return true' => [true, __FILE__],
            'no path, should return false' => [false, null],
        ];
    }

}