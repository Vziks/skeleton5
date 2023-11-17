<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;


$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    //    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();

return $config->setFinder($finder)
    ->setRules([
        '@PhpCsFixer' => true,
        '@DoctrineAnnotation' => true,
        '@Symfony:risky' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'yoda_style' => false
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(false);