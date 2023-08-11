<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('config')
    ->exclude('var/')
//    ->exclude('tests/')
    ->exclude('.git/')
    ->exclude('.vscode/')
    ->exclude('.idea/')
    ->notPath('bin/console')
    ->notPath('bin/phpunit')
    ->notPath('public/index.php')
    ->notPath('rector.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'linebreak_after_opening_tag' => true,
		'mb_str_functions' => true,
		'no_php4_constructor' => true,
		'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
		'phpdoc_order' => true,
		'strict_comparison' => true,
		'strict_param' => true,
		'blank_line_between_import_groups' => true,
//
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,    // this also needs: ->setRiskyAllowed(true)
//
//        // override some @PHPxxMigration rules, because CG supports only php v7.3
        'assign_null_coalescing_to_coalesce_equal' => false, // override @PHP81Migration, ??= requires php v7.4
        'use_arrow_functions' => true,     // override @PHP80Migration:risky, => fn() requires php v7.4
// //        'modernize_strpos' => false,        // override @PHP80Migration:risky, str_contains() requires php v8.0
//
//        // override some @Symfony rules
        'binary_operator_spaces' => ['operators' => ['=' => null, '=>' => null, 'and' => null, 'or' => null]],
        'blank_line_before_statement' => false,
        'class_attributes_separation' => false,
        'increment_style'=> ['style' => 'post'],
        'phpdoc_to_comment' => true,

        '@DoctrineAnnotation' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => true,
        'cast_spaces' => ['space' => 'none'],
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'none'],
        'dir_constant' => true,
//		'function_to_constant' => [
//			'functions' => [
//				'get_called_class',
//				'get_class',
//				'get_class_this',
//				'php_sapi_name',
//				'phpversion',
//				'pi']
//		],
        'lowercase_cast' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'modernize_strpos' => true,
        'modernize_types_casting' => true,
//        'native_function_casing' => true,
//        'new_with_braces' => true,
//        'no_alias_functions' => true,
//        'no_blank_lines_after_phpdoc' => true,
//        'no_empty_phpdoc' => true,
//        'no_empty_statement' => true,
//        'no_extra_blank_lines' => true,
//        'no_leading_import_slash' => true,
//        'no_leading_namespace_whitespace' => true,
//		'no_multiline_whitespace_around_double_arrow' => true,
//		'multiline_whitespace_before_semicolons' => true,
//		'no_singleline_whitespace_before_semicolons' => true,
//		'object_operator_without_whitespace' => true,
//		'standardize_not_equals' => true,
//		'ternary_operator_spaces' => true,
//        'no_null_property_initialization' => true,
//        'no_short_bool_cast' => true,
//        'no_superfluous_elseif' => true,
//        'no_trailing_comma_in_singleline_array' => true,
//        'no_unneeded_control_parentheses' => true,
//        'no_unused_imports' => true,
//        'no_useless_else' => true,
//        'no_whitespace_in_blank_line' => true,
        'single_import_per_statement' => false,
        'ordered_imports' => ['sort_algorithm' => 'length', 'imports_order' => ['class', 'function', 'const']],
        'global_namespace_import' => [
			'import_constants' => false,
			'import_functions' => false,
			'import_classes' => false
		],
//        'php_unit_construct' => ['assertions' => ['assertEquals', 'assertSame', 'assertNotEquals', 'assertNotSame']],
//        'php_unit_mock_short_will_return' => true,

		'php_unit_construct' => true,
		'php_unit_data_provider_name' => false,
		'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],

		'phpdoc_no_access' => true,
        'phpdoc_no_empty_return' => false,
        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'return_type_declaration' => ['space_before' => 'none'],
        'single_quote' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_trait_insert_per_statement' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'whitespace_after_comma_in_array' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__.'/.php-cs-fixer.cache');
