<?php

namespace Main\Helper;

class Data
{

    static function parseCsv($file, $delim = '\;')
    {

        $data = file_get_contents($file);

        $lines = preg_split('/[\n\r]+/', $data);

        $line_headers = $lines[0];

        $headers = preg_split('/' . $delim . '/', $line_headers);

        foreach ($headers as &$header) {
            $header = trim($header, '"');
        }

        array_shift($lines);

        $rows = [];

        foreach ($lines as $line) {
            if (trim($line)) {
                $fields = preg_split('/' . $delim . '/', $line);

                foreach ($fields as $i => &$field) {
                    $f4ield = trim($field, '"');
                    $row[$headers[$i]] = $field;
                }
            }

            if (isset($row['id'])) {
                $rows[$row['id']] = $row;
            } else {
                $rows[] = $row;
            }
        }

        return $rows;
    }

}
