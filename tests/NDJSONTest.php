<?php

namespace Tests;

use Sunaoka\Ndjson\NDJSON;
use PHPUnit\Framework\TestCase;

class NDJSONTest extends TestCase
{
    public function test_readline_successful()
    {
        $ndjson = new NDJSON(__DIR__ . '/fixtures/001.ndjson');

        $actual = $ndjson->readline();
        self::assertSame(['test' => '001'], $actual);

        $actual = $ndjson->readline();
        self::assertSame(['test' => '002'], $actual);
    }

    public function test_readlines_successful()
    {
        $ndjson = new NDJSON(__DIR__ . '/fixtures/001.ndjson');

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
    }
}
