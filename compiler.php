<?php
error_reporting(error_level: E_ALL & ~E_NOTICE & ~E_USER_NOTICE);

use Dotenv\Dotenv;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;
use function \Sentry\init;

$path = realpath(path: __DIR__ . '/../../../');
$autoload = realpath(path: "$path/vendor/autoload.php");
if($autoload === false)
{
    throw new Exception(message: "Unabble to find \"vendor/autoload.php\". Please adapt \$path. ");
}
else
{
    require_once $autoload;
    $scss = new Compiler();
    $scss->setOutputStyle(style: OutputStyle::COMPRESSED);
    $scss->setSourceMap(sourceMap: Compiler::SOURCE_MAP_FILE);
    $dotenv = Dotenv::createImmutable(paths: $path);
    $dotenv->load();
    $dotenv->required(variables: 'WORKSPACE');
    $dotenv->required(variables: 'SCSSPATH');
    $dotenv->required(variables: 'VENDOR');
    $dotenv->required(variables: 'AUTOLOAD');
    $dotenv->required(variables: 'DOCROOT');
    $dotenv->required(variables: "STATIC");
    $dotenv->required(variables: "CSSPATH");

    if(empty($_ENV['SENTRY']))
    {
        init(options: [ 'dsn' => strval(value: $_ENV['SENTRY']) ]);
    }

    if(strval(value: $_ENV["WORKSPACE"]) !== $path or strval(value: $_ENV["AUTOLOAD"]) !== $autoload)
    {
        throw new Exception(message: "SCHLIMM: Unsauberer Pfad zu \"vendor/autoload.php\". Pruefen sie die ENV Variablen.");
    }

    $scss->setSourceMapOptions(sourceMapOptions: [ 
        'sourceMapWriteTo'  => realpath(path: $_ENV["CSSPATH"] . '/debug.min.css.map'),
        'sourceMapURL'      => 'debug.min.css.map',
        'sourceMapFilename' => 'debug.min.css',
        'sourceMapBasepath' => realpath(path: $_ENV["WORKSPACE"]),
        'sourceRoot'        => realpath($_ENV["SCSSPATH"]),
    ]);

    // Alle SCSS-Dateien im Verzeichnis finden
    foreach(glob( "scss/*.scss") as $scssFile)
    {
        // Überprüfe, ob der Dateiname nicht mit '_' beginnt
        if(basename($scssFile)[0] !== '_')
        {
            $cssFile = $_ENV["CSSPATH"] . DIRECTORY_SEPARATOR . basename(path: $scssFile, suffix: '.scss') . '.min.css';
            $sourceMapFile = $cssFile . '.map';

            try
            {
                $compiled = $scss->compileString(source: file_get_contents(filename: $scssFile), url: $scssFile);
                file_put_contents(filename: $cssFile, data: $compiled->getCss());
                file_put_contents(filename: $sourceMapFile, data: $compiled->getSourceMap());
                trigger_error(message: "Kompiliert: $scssFile -> $cssFile\n", error_level: E_USER_NOTICE);
                trigger_error(message: "Source-Map: $sourceMapFile\n", error_level: E_USER_NOTICE);
            }
            catch (Throwable $e)
            {
                throw new Exception(message: "Fehler beim Kompilieren von $scssFile: {$e->getMessage()}\n");
            }
        }
    }
}
