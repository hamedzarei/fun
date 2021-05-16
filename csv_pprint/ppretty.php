<?php

$maxLenItems = [];
$existsOverMax = [];
$columnOverMax = [];
$maxRowColumnLen = [];
$validMaxColumnLen = 80;

function findColumnMaxLen($content, $validMaxColumnLen, $columnMaxLen = 1) {
    global $existsOverMax;
    global $maxLenItems;
    foreach ($content[0] as $columnIndex => $value) {
        $maxLen = findMaxLen($content, $columnIndex, $validMaxColumnLen);
        $maxLenItems[$columnIndex] = $maxLen;
        $columnMaxLen += $maxLen;
        $columnMaxLen += 1;
    }

    return [
        'columnMaxLen' => $columnMaxLen,
        'maxLenItems' => $maxLenItems
    ];
}

function findMaxLen($data, $columnIndex, $validMaxColumnLen) {
    global $existsOverMax;
    global $columnOverMax;
    global $maxRowColumnLen;
    $max = -1;
    foreach ($data as $rowIndex => $item) {
        $itemLen = strlen($item[$columnIndex]);
        if ($itemLen > $max) {
            $max = strlen($item[$columnIndex]);
        }

        // find max column of each rows
        if (isset($maxRowColumnLen[$rowIndex])) {
            if ($itemLen > $maxRowColumnLen[$rowIndex]) {
                $maxRowColumnLen[$rowIndex] = $itemLen;
            }
        } else {
            $maxRowColumnLen[$rowIndex] = $itemLen;
        }

        // checked if row is overflowed
        if ($itemLen > $validMaxColumnLen) {
            $existsOverMax[$rowIndex] = true;
            $columnOverMax[] = $columnIndex;
        }
    }

    if ($max > $validMaxColumnLen) {
        return $validMaxColumnLen;
    }
    return $max;
}

function readCSV($path) {
    $content = file_get_contents($path);

    $content = array_map("str_getcsv", explode("\n", $content));

    return $content;
}

function printBorder($columnMaxLen) {
    for ($i = 0; $i < $columnMaxLen; $i++) {
        if ($i == 0 || $i == $columnMaxLen - 1) echo "+";
        else echo "-";
    }
    echo "\n";
}

function makeNewData($row, $rowIndex) {
    global $validMaxColumnLen;
    global $maxRowColumnLen;
    global $maxLenItems;
    $numRows = intval($maxRowColumnLen[$rowIndex] / $validMaxColumnLen) + 1;
    $newData = [];

    foreach ($row as $columnIndex => $item) {
        $lenItem = strlen($item);
        $newItem = $item;
        $itemLen = strlen($item);
        $itemRows = intval($itemLen / $validMaxColumnLen) + 1;
        for ($i = 0; $i < $numRows; $i++) {
            if ($i < $itemRows) {
                if ($lenItem > $validMaxColumnLen) {
                    // up to max valid len
                    $newItem = substr($newItem, 0, $validMaxColumnLen);
                    // find last space
                    $lastSpace = strripos($newItem, " ");
                    // capture new item string if find last space
                    $newItem = substr($newItem, 0, ($lastSpace === false) ? strlen($newItem):$lastSpace);
                    $newData[$i][$columnIndex] = $newItem;
                    // create remain string item as new item
                    $newItem = substr($item, $lastSpace + 1);
                    $lenItem = strlen($newItem);
                } else {
                    $newData[$i][$columnIndex] = $newItem;
                }
            } else {
                $newData[$i][$columnIndex] = '';
            }
        }
    }

    $maxLenNewItems = $maxLenItems;
    foreach ($newData as $rowIndex => $row) {
        foreach ($row as $columnIndex => $item) {
            if (!isset($maxLenNewItems[$columnIndex])) {
                if ($maxLenItems[$columnIndex] < strlen($item)) {
                    $maxLenNewItems[$columnIndex] = strlen($item);
                }
            }
            else {
                if ($maxLenNewItems[$columnIndex] < strlen($item)) {
                    $maxLenNewItems[$columnIndex] = strlen($item);
                }
            }
        }
    }

    return [
        'data' => $newData,
        'maxLenItems' => $maxLenNewItems
    ];
}

function printRow($row, $rowIndex, $maxLenItems) {
    global $validMaxColumnLen;
    global $existsOverMax;

    if (isset($existsOverMax[$rowIndex]) && $existsOverMax[$rowIndex]) {
        $existsOverMax[$rowIndex] = false;
        // make new data
        $newItems = makeNewData($row, $rowIndex);

        foreach ($newItems['data'] as $newRow) {
            printRow($newRow, $rowIndex, $newItems['maxLenItems']);
        }
    } else {
        echo "|";
        foreach ($row as $columnIndex => $item) {
            $itemLen = strlen($item);
            echo $item.str_repeat(" ", $maxLenItems[$columnIndex] - $itemLen)."|";
        }
        echo "\n";
    }
}

function printTable($content, $columnMaxLen, $maxLenItems, $validMaxColumnLen) {
    printBorder($columnMaxLen);
    foreach ($content as $rowIndex => $row) {
        printRow($row, $rowIndex, $maxLenItems);
        printBorder($columnMaxLen);
    }
}

$content = readCSV('input.csv');

$numRows = count($content) * 2 + 1;
$numColumns = count($content[0]) * 2 + 1;

// find max column
$maxData = findColumnMaxLen($content, $validMaxColumnLen);

printTable($content, $maxData['columnMaxLen'], $maxData['maxLenItems'], $validMaxColumnLen);

