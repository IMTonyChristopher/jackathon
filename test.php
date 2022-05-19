<?php

function compress (string $input): string
{
    $table = [];
    foreach(explode(" ", $input) as $word) {
        $word = trim($word, '\t\n\r\0\x0B,\.;:\s');
        if (strlen($word) < 4) {
            continue;
        }
        if (! array_key_exists($word, $table)) {
            $table[$word] = 0;
        }
        $table[$word]++;
    }
    $nextChar = 0x1F600;
    $strings = [];
    $chars = [];
    foreach($table as $key => $count) {
        if ($count < 2) {
            unset($table[$key]);
        }
        $chars[] = IntlChar::chr($nextChar++);
        $strings[] = $key;
    }

    $output = join(',', $strings) . '\n';
    //var_dump($output);
    $output .= str_replace($strings, $chars, $input);

    return $output;
}

function decompress (string $input, string $firstLine, string $secondLine): string
{
    $output = $input;

    return $output;
}

function test (): void
{
    $files = scandir('fixtures');
    $ratios = [];

    foreach ($files as $file) {
        if (!preg_match('/\.(css|json|txt)$/', $file)) {
            continue;
        }

        $input = file_get_contents('fixtures/' . $file);

        $compressed = compress($input);

        $inputArr = explode("\n", $compressed);
        $firstLine = $inputArr[0];
        $secondLine = $inputArr[1];

        $decompressed = decompress($compressed, $firstLine, $secondLine);

        if ($decompressed !== $input) {
            echo "FAIL: Outputs do not match!\n";
        }

        $ratio = (1 - (mb_strlen($compressed) / mb_strlen($input))) * 100;
        $ratios[$file] = $ratio;
        echo 'File: ' . $file . ', Ratio: ' . round($ratio) . "%\n";
    }

    $ratioAverage = array_reduce($ratios, fn($carry, $ratio) => $carry + $ratio) / count($ratios);

    echo 'Average Compression Ratio: ' . round($ratioAverage, 2) . "%\n";
}

test();
