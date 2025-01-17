<?php
/**
 * SPDX-FileCopyrightText: 2010 - 2024 the Friendica project
 *
 * SPDX-License-Identifier: CC0-1.0
 */

declare(strict_types=1);

require_once __DIR__ . '/bin/dev/php-cs-fixer/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__)
	->notPath('addon')
	->notPath('bin/dev')
	->notPath('config')
	->notPath('doc')
	->notPath('images')
	->notPath('mods')
	->notPath('spec')
	->notPath('vendor')
	->notPath('view/asset')
	->notPath('lang')
	->notPath('view/smarty3/compiled');

$config = new PhpCsFixer\Config();
return $config
	->setRules([
		'@PSR1'                   => true,
		'@PSR2'                   => true,
		'@PSR12'                  => true,
		'align_multiline_comment' => true,
		'array_indentation'       => true,
		'array_syntax'            => [
			'syntax' => 'short',
		],
		'binary_operator_spaces' => [
			'default'   => 'single_space',
			'operators' => [
				'=>' => 'align_single_space_minimal',
				'='  => 'align_single_space_minimal',
				'??' => 'align_single_space_minimal',
			],
		],
		'blank_line_after_namespace'   => true,
		'braces_position'        => [
			'anonymous_classes_opening_brace'  => 'same_line',
			'control_structures_opening_brace' => 'same_line',
			'functions_opening_brace'          => 'next_line_unless_newline_at_signature_end',
		],
		'elseif'               => true,
		'encoding'             => true,
		'full_opening_tag'     => true,
		'function_declaration' => [
			'closure_function_spacing' => 'one',
		],
		'indentation_type' => true,
		'line_ending'      => true,
		'list_syntax'      => [
			'syntax' => 'long',
		],
		'lowercase_keywords'                 => true,
		'no_closing_tag'                     => true,
		'no_spaces_after_function_name'      => true,
		'spaces_inside_parentheses'          => false,
		'no_trailing_whitespace'             => true,
		'no_trailing_whitespace_in_comment'  => true,
		'no_unused_imports'                  => true,
		'single_blank_line_at_eof'           => true,
		'single_class_element_per_statement' => true,
		'single_import_per_statement'        => true,
		'single_line_after_imports'          => true,
		'switch_case_space'                  => true,
		'ternary_operator_spaces'            => false,
		'visibility_required'                => [
			'elements' => ['property', 'method']
		],
		'new_with_parentheses' => true,
	])
	->setFinder($finder)
	->setIndent("\t");
