<?php
$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(true)
    ->ignoreUnreadableDirs()
    ->notPath('bootstrap')
    ->notPath('storage')
    ->notPath('vendor')
    ->notPath('logs')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'full_opening_tag' => false,
    ])
    ->setFinder($finder);
