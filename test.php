<?php

$convertTable = [
    [
        'string' => 'and',
        'char' => '😀'
    ],
    [
        'string' => 'the',
        'char' => '😄'
    ],
    [
        'string' => 'that',
        'char' => '😁'
    ],
    [
        'string' => 'have',
        'char' => '😆'
    ],
    [
        'string' => 'for',
        'char' => '😅'
    ],
    [
        'string' => 'not',
        'char' => '🤣'
    ],
    [
        'string' => 'with',
        'char' => '😂'
    ],
    [
        'string' => 'you',
        'char' => '🙂'
    ],
    [
        'string' => 'this',
        'char' => '🙃'
    ],
    [
        'string' => 'but',
        'char' => '😉'
    ],
    [
        'string' => 'his',
        'char' => '😊'
    ],
    [
        'string' => 'from',
        'char' => '😇'
    ],
    [
        'string' => 'they',
        'char' => '🥰'
    ],
    [
        'string' => 'say',
        'char' => '😍'
    ],
    [
        'string' => 'her',
        'char' => '🤩'
    ],
    [
        'string' => 'she',
        'char' => '😘'
    ],
    [
        'string' => 'will',
        'char' => '😗'
    ],
    [
        'string' => 'one',
        'char' => '☺'
    ],
    [
        'string' => 'all',
        'char' => '😚'
    ],
    [
        'string' => 'would',
        'char' => '😙'
    ],
    [
        'string' => 'there',
        'char' => '😋'
    ],
    [
        'string' => 'their',
        'char' => '😛'
    ],
    [
        'string' => 'what',
        'char' => '😜'
    ],
    [
        'string' => 'out',
        'char' => '🤪'
    ],
    [
        'string' => 'about',
        'char' => '😝'
    ],
    [
        'string' => 'who',
        'char' => '🤑'
    ],
    [
        'string' => 'get',
        'char' => '🤗'
    ],
    [
        'string' => 'which',
        'char' => '🤭'
    ],
    [
        'string' => 'when',
        'char' => '🤫'
    ],
    [
        'string' => 'make',
        'char' => '🤔'
    ],
    [
        'string' => 'can',
        'char' => '🤐'
    ],
    [
        'string' => 'like',
        'char' => '🤨'
    ],
    [
        'string' => 'time',
        'char' => '😐'
    ],
    [
        'string' => 'just',
        'char' => '😑'
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
    global $convertTable;
    $output = $input;
    foreach ($convertTable as $replacement) {
        $char = $replacement['char'];
        $output = preg_replace("/$char/", $replacement['string'], $output);
    }

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
