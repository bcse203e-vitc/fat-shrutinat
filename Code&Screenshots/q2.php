<?php
$filename="textdoc.txt";
function normalizetext($filename,$mode) {
    if (!file_exists($filename)) {
        die("File not found.");
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    $corrected= [];
    $whitespace= 0;
    $punctuation= [];

    foreach ($lines as $linenum=> $line) {

        $newline = preg_replace('/[ \t]+/', ' ', $line, -1, $count1);
        $whitespace += $count1;

        $trimmed = ltrim($newline);
        if ($trimmed!== $newline) $whitespace++;
        $newline = rtrim($trimmed);

        if (preg_match('/^[[:punct:]]+$/', $newline)) {
            $punctuation[] = $linenum+ 1; 
        }

        $corrected[] = $newline;
    }

    if ($mode === "compress") {
        $compressed= [];
        $Flag = false;

        foreach ($corrected as $line) {
            if ($line === "") {
                if (!$Flag) {
                    $compressed[] = "";
                    $Flag = true;
                }
            } else {
                $compressed[] = $line;
                $Flag = false;
            }
        }
        $corrected = $compressed;
    }

    if ($mode ==="expand") {
        $expanded = [];
        foreach ($corrected as $line) {
            $expanded[] = $line;
            $expanded[] = "";  
        }
        $corrected= $expanded;
    }

    file_put_contents($fileName, implode(PHP_EOL, $corrected));


    if (!empty($punctuation)) {
        echo "Lines containing only punctuation: " . implode(", ", $punctuation) . "\n";
    }

    return $whitespace;
}
?>
