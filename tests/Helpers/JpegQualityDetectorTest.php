<?php

namespace WebPConvert\Tests\Convert\Helpers;

use WebPConvert\Helpers\JpegQualityDetector;

use PHPUnit\Framework\TestCase;

class JpegQualityDetectorTest extends TestCase
{

    private static $imgDir = __DIR__ . '/../images';

    public function testDetectQualityOfJpg()
    {
        $result = JpegQualityDetector::detectQualityOfJpg(self::$imgDir . '/small-q61.jpg');
        if (is_null($result)) {
            $this->addToAssertionCount(1);
        } else {
            $this->assertSame(61, $result);
        }
    }


    public function testDetectQualityOfJpgNonExistantFile()
    {
        $result = JpegQualityDetector::detectQualityOfJpg('i dont exist');

        $this->assertNull($result);
    }

}
