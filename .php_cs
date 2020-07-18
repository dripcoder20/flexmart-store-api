<?php

/**
 * Config for PHP-CS-Fixer ver2
 */

$rules = [
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
];

$excludes = [
    'vendor',
    'node_modules',
    'storage',
];

return PhpCsFixer\Config::create()
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude($excludes)
            ->notName('README.md')
            ->notName('*.xml')
            ->notName('*.yml')
    );

