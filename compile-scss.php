<?php
require_once (realpath(path: __DIR__ . '/../../../vendor/autoload.php'));

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

$scss = new Compiler();
$scss->setOutputStyle(OutputStyle::COMPRESSED);
$scss->setSourceMap(Compiler::SOURCE_MAP_FILE);

$scss->setSourceMapOptions([
    'sourceMapWriteTo'  => realpath(__DIR__ . '/../../../public/static/css/debug.min.css.map'),
    'sourceMapURL'      => 'debug.min.css.map', 
    'sourceMapFilename' => 'debug.min.css',
    'sourceMapBasepath' => realpath(__DIR__),
    'sourceRoot'        => '/scss',
]);

$inputDir = __DIR__ . '/scss';
$outputDir = realpath(__DIR__ . '/../../../public/static/css');

// Alle SCSS-Dateien im Verzeichnis finden
foreach (glob("$inputDir/*.scss") as $scssFile) {
    $cssFile = "$outputDir/" . basename($scssFile, '.scss') . '.min.css';
    $sourceMapFile = $cssFile . '.map';

    try {
        $compiled = $scss->compileString(file_get_contents($scssFile), $scssFile);
        file_put_contents($cssFile, $compiled->getCss());
        file_put_contents($sourceMapFile, $compiled->getSourceMap());
        // echo "Kompiliert: $scssFile -> $cssFile\n";
        // echo "Source-Map: $sourceMapFile\n";
    } catch (Exception $e) {
        echo "Fehler beim Kompilieren von $scssFile: {$e->getMessage()}\n";
    }
}
