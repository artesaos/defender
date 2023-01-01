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

return (new PhpCsFixer\Config())
    ->setRules(array(
      '@PSR2' => true,
      // addtional rules
      'array_syntax' => ['syntax' => 'short'],
      'multiline_whitespace_before_semicolons' => true,
      'echo_tag_syntax' => true,
      'no_unused_imports' => true,
      'not_operator_with_successor_space' => true,
    ))
    ->setFinder($finder);
