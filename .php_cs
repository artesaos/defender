<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->files()
    ->in(__DIR__)
    ->exclude('vendor')
    ->notName("*.txt")
    ->notName("*.phar")
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
;

return PhpCsFixer\Config::create()
    ->setRules(array(
      '@PSR2' => true,
      // addtional rules
      'array_syntax' => ['syntax' => 'short'],
      'no_multiline_whitespace_before_semicolons' => true,
      'no_short_echo_tag' => true,
      'no_unused_imports' => true,
      'not_operator_with_successor_space' => true,
    ))
    ->setFinder($finder);
