<?php

$convertTable = [
  [
    'string' => 'and',
    'char'   => '😀'
  ],
  [
    'string' => 'the',
    'char'   => '😂'
  ],
];

function compress (string $input): string
{
    global $convertTable;

    foreach ($convertTable as $item) {
        $strings[] = $item['string'];
        $chars[] = $item['char'];
    }

    $output = str_replace($strings, $chars, $input);

    return $output;
}

function decompress (string $input): string
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
        $decompressed = decompress($compressed);

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
