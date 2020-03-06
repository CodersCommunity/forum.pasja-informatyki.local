<?php

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @dataProvider qa_js_dataprovider
     */
    public function test__qa_js($input, bool $forceQuotes, $expectedResult): void
    {
        $test = qa_js($input, $forceQuotes);
        $this->assertSame($expectedResult, $test);
    }

    /**
     * @dataProvider qa_version_to_float_dataprovider
     */
    public function test__qa_version_to_float($version, $expectedResult): void
    {
        $test = qa_version_to_float($version);
        $this->assertSame($expectedResult, $test);
    }

    public function qa_version_to_float_dataprovider(): array
    {
        return [
            ['1.0', 1.0],
            ['1.6.2.2', 1.006002002],
            [1.6, 1.006],
        ];
    }

    public function qa_js_dataprovider(): array
    {
        return [
            [ 'test', false, "'test'"],
            [ 'test', true, "'test'"],
            [ 123, false, 123],
            [ 123, true, "'123'"],
            [ true, false, 'true'],
            [ true, true, "'true'"],
        ];
    }
}