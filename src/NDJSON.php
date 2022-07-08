<?php

namespace Sunaoka\Ndjson;

use Generator;
use SplFileObject;

class NDJSON extends SplFileObject
{
    /**
     * Gets line from file and decode a JSON string
     *
     * @return array|null
     */
    public function readline()
    {
        $row = trim($this->fgets());
        if ($row === '') {
            return null;
        }

        return json_decode($row, true);
    }

    /**
     * Get lines from file and decode a JSON string
     *
     * @param int $lines
     *
     * @return Generator
     */
    public function readlines($lines)
    {
        $count = 0;
        $rows = [];

        while ($this->eof() === false) {
            $row = $this->readline();
            if ($row === null) {
                continue;
            }
            $rows[] = $row;
            if (++$count === $lines) {
                yield $rows;
                $count = 0;
                $rows = [];
            }
        }

        yield $rows;
    }
}
