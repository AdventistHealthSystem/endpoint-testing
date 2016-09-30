<?php

namespace EndpointTesting\Tests\Log;

use EndpointTesting\Log\File;
use EndpointTesting\Log\Parser;
use EndpointTesting\Log\Exception;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $sut = new \EndpointTesting\Log\Parser;
        $file = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['getLines', 'validatePath', 'getAdapter'])
            ->getMock();

        $sut->__construct($file);
    }

    /**
     * Tests the EndpointTesting\Log\Parser::getUrls method
     * @dataProvider provideGetUrls
     */
    public function testGetUrls($expected)
    {
        $adapter = new \EndpointTesting\Log\File\Adapter\Iis;
        $sut = $this->getMockBuilder('\EndpointTesting\Log\Parser')
            ->setMethods(['getUrl'])
            ->getMock();
        $file = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['getLines', 'validatePath', 'getAdapter'])
            ->getMock();

        $file->expects($this->once())
            ->method('getLines')
            ->will($this->returnValue($expected));

        $file->expects($this->once())
            ->method('getAdapter')
            ->will($this->returnValue($adapter));

        $sut->expects($this->exactly(count($expected)))
            ->method('getUrl')
            ->will($this->returnValue($expected[0]));

        $result = $sut->getUrls($file);
        $this->assertEquals($expected, $result);
    }

    public function provideGetUrls()
    {
        return [
            'simple test' => [
                'expected' => ['value'],
            ],
        ];
    }

    /**
     * Testing the EndpointTesting\Log\Parser::getUrl method.
     * @dataProvider provideGetUrl
     */
    public function testGetUrl($expected, $pattern, $line)
    {
        $sut = new \EndpointTesting\Log\Parser;
        $result = $sut->getUrl($line, $pattern);
        $this->assertEquals($expected, $result);
    }

    public function provideGetUrl()
    {
        return [
            'IIS image test' => [
                'expected' => 'img/news/thumbs/article_2138.jpg -',
                'pattern' => \EndpointTesting\Log\File\Adapter\Iis::REGEX_PATTERN,
                'line'  => ' 2016-09-21 00:00:38 192.168.85.48 GET /img/news/thumbs/article_2138.jpg - 80 - 192.168.85.52 Mozilla/5.0+(Windows+NT+6.1;+Trident/7.0;+rv:11.0)+like+Gecko 200 0 0 0',
            ],

            'IIS page request with params' => [
                'expected' => 'page.php section=news&page=article&id=1849',
                'pattern' => \EndpointTesting\Log\File\Adapter\Iis::REGEX_PATTERN,
                'line' => '2016-09-21 00:01:37 192.168.85.48 GET /page.php section=news&page=article&id=1849 80 - 192.168.85.52 Mozilla/5.0+(compatible;+DotBot/1.1;+http://www.opensiteexplorer.org/dotbot,+help@moz.com) 200 0        0 452'
            ],

            'Apache image request' => [
                'expected' => '/img/features/fullsize/feature_.jpg',
                'pattern' => \EndpointTesting\Log\File\Adapter\Apache::REGEX_PATTERN,
                'line' => '127.0.0.1 - - [23/May/2016:09:52:50 -0400] "GET /img/features/fullsize/feature_.jpg HTTP/1.1" 403 305'
            ],

            'Apache page request with params' => [
                'expected' => '/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5',
                'pattern' => \EndpointTesting\Log\File\Adapter\Apache::REGEX_PATTERN,
                'line' => '127.0.0.1 - - [23/May/2016:09:55:10 -0400] "GET /plugins/fancybox/source/jquery.fancybox.css?v=2.1.5 HTTP/1.1" 403 314'
            ],

            'expect nothing' => [
                'expected' => null,
                'pattern' => \EndpointTesting\Log\File\Adapter\Apache::REGEX_PATTERN,
                'line' => 'arbitrary line, that would not find anything',
            ],
        ];
    }

    public function testSetFile()
    {
        $sut = new \EndpointTesting\Log\Parser;
        $file = $this->getMockBuilder('\EndpointTesting\Log\File')
            ->setMethods(['setPath', 'setFileAdapter'])
            ->getMock();

        $result = $sut->setFile($file);
        $this->assertEquals($sut, $result);

        $property = new \ReflectionProperty('\EndpointTesting\Log\Parser', 'file');
        $property->setAccessible(true);
        $result = $property->getValue($sut);

        $this->assertEquals($file, $result);
    }

    public function testGetFile()
    {
        $sut = new \EndpointTesting\Log\Parser;
        $file = 'some file object';

        $property = new \ReflectionProperty('\EndpointTesting\Log\Parser', 'file');
        $property->setAccessible(true);
        $result = $property->setValue($sut, $file);

        $result = $sut->getFile($file);
        $this->assertEquals($file, $result);
    }
}
