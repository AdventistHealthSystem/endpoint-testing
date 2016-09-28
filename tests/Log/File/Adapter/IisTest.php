<?php

namespace EndpointTesting\Tests\Log\File\Adapter;

use EndpointTesting\Log\File;
use EndpointTesting\Log\Exception;

class IisTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the EndpointTesting\Tests\Log\File\Adapter\Apache::isParsable method
     * @dataProvider provideIsParsable
     */
    public function testIsParsable($expected, $input)
    {
        $subject = new \EndpointTesting\Log\File\Adapter\Iis;
        $result = $subject->isParsable($input);
        $this->assertEquals($expected, $result);
    }

    public function provideIsParsable()
    {
        return [
            [false, [
                '127.0.0.1 - - [23/May/2016:09:54:59 -0400] "GET /img/features/fullsize/feature_.jpg HTTP/1.1" 403 305',
            ]],
            [false, [
                '127.0.0.1 - - [23/May/2016:09:55:10 -0400] "GET /plugins/fancybox/source/jquery.fancybox.css?v=2.1.5 HTTP/1.1" 403 314'
            ]],
            [false, [
                '127.0.0.1 - - [23/May/2016:11:25:46 -0400] "GET /page.php?section=suppliers&page=overview HTTP/1.1" 200 1873',
            ]],
            [true, [
                '2016-09-21 00:00:38 192.168.85.48 GET /img/news/thumbs/article_2138.jpg - 80 - 192.168.85.52 Mozilla/5.0+(Windows+NT+6.1;+Trident/7.0;+rv:11.0)+like+Gecko 200 0 0 0',
            ]],
            [true, [
                '#Software: Microsoft Internet Information Services 7.5',
            ]],
        ];
    }

}