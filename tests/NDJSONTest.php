<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Sunaoka\Ndjson\NDJSON;

class NDJSONTest extends TestCase
{
    /**
     * @dataProvider readlineProvider
     *
     * @param string $file
     *
     * @return void
     */
    public function test_readline_newline_successful($file)
    {
        $ndjson = new NDJSON($file);

        $actual = $ndjson->readline();
        self::assertSame(['test' => '001'], $actual);

        $actual = $ndjson->readline();
        self::assertSame(['test' => '002'], $actual);
    }

    /**
     * @return string[][]
     */
    public static function readlineProvider()
    {
        return [
            [__DIR__ . '/fixtures/basic-lf.ndjson'],
            [__DIR__ . '/fixtures/basic-crlf.ndjson'],
        ];
    }

    /**
     * @dataProvider emptyLineProvider
     *
     * @param string $file
     *
     * @return void
     */
    public function test_readline_empty_line_successful($file)
    {
        $ndjson = new NDJSON($file);

        $expected = 1;
        while ($json = $ndjson->readline()) {
            self::assertSame(sprintf('%03d', $expected++), $json['test']);
        }
    }

    /**
     * @return string[][]
     */
    public static function emptyLineProvider()
    {
        return [
            [__DIR__ . '/fixtures/empty-line-lf.ndjson'],
            [__DIR__ . '/fixtures/empty-line-crlf.ndjson'],
        ];
    }

    /**
     * @return void
     */
    public function test_readline_empty_successful()
    {
        $ndjson = new NDJSON(__DIR__ . '/fixtures/empty.ndjson');

        $actual = $ndjson->readline();
        self::assertNull($actual);
    }

    /**
     * @dataProvider readlinesProvider
     *
     * @param string $file
     *
     * @return void
     */
    public function test_readlines_newline_successful($file)
    {
        $ndjson = new NDJSON($file);

        $generator = $ndjson->readlines(3);

        $actual = $generator->current();
        self::assertSame([['test' => '001'], ['test' => '002'], ['test' => '003']], $actual);

        $generator->next();
        $actual = $generator->current();
        self::assertSame([['test' => '004'], ['test' => '005']], $actual);

        $ndjson->rewind();

        $generator = $ndjson->readlines(5);
        $actual = $generator->current();
        self::assertSame([['test' => '001'], ['test' => '002'], ['test' => '003'], ['test' => '004'], ['test' => '005']], $actual);

        $ndjson->rewind();

        $generator = $ndjson->readlines(10);
        $actual = $generator->current();
        self::assertSame([['test' => '001'], ['test' => '002'], ['test' => '003'], ['test' => '004'], ['test' => '005']], $actual);

        $ndjson->rewind();

        $actual = 0;
        foreach ($ndjson->readlines(1) as $json) {
            $actual++;
        }

        self::assertSame(5, $actual);

        $ndjson->rewind();

        $actual = 0;
        foreach ($ndjson->readlines(5) as $json) {
            $actual++;
        }

        self::assertSame(1, $actual);

        $ndjson->rewind();

        $actual = 0;
        foreach ($ndjson->readlines(10) as $json) {
            $actual++;
        }

        self::assertSame(1, $actual);
    }

    /**
     * @return string[][]
     */
    public static function readlinesProvider()
    {
        return [
            [__DIR__ . '/fixtures/basic-lf.ndjson'],
            [__DIR__ . '/fixtures/basic-crlf.ndjson'],
        ];
    }

    /**
     * @return void
     */
    public function test_readlines_empty_successful()
    {
        $ndjson = new NDJSON(__DIR__ . '/fixtures/empty.ndjson');

        $generator = $ndjson->readlines(10);
        $actual = $generator->current();

        self::assertNull($actual);
    }

    /**
     * @return void
     */
    public function test_writeline_successful()
    {
        $ndjson = new NDJSON('php://memory');

        $actual = $ndjson->writeline(['test' => '001']);
        self::assertSame(15, $actual);

        $actual = $ndjson->writeline(['test' => '002'], JSON_PRETTY_PRINT);
        self::assertSame(15, $actual);

        $ndjson->rewind();

        $actual = $ndjson->readline();
        self::assertSame(['test' => '001'], $actual);

        $actual = $ndjson->readline();
        self::assertSame(['test' => '002'], $actual);
    }

    /**
     * @return void
     */
    public function test_writelines_successful()
    {
        $ndjson = new NDJSON('php://memory');

        $actual = $ndjson->writelines([['test' => '001'], ['test' => '002']]);
        self::assertSame(30, $actual);

        $actual = $ndjson->writelines([['test' => '003'], ['test' => '004']], JSON_PRETTY_PRINT);
        self::assertSame(30, $actual);

        $actual = $ndjson->writelines([['test' => '005']], JSON_PRETTY_PRINT);
        self::assertSame(15, $actual);

        $ndjson->rewind();

        $generator = $ndjson->readlines(5);
        $actual = $generator->current();
        self::assertSame([['test' => '001'], ['test' => '002'], ['test' => '003'], ['test' => '004'], ['test' => '005']], $actual);
    }
}
