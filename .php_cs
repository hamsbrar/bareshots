<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = array(
    /*
        this is PHP-cs fixer configuration used/required to format this project correctly
    */

    // ------------------------------------------------------------------- namespaces

    'clean_namespace'                    => true,
    'blank_line_after_namespace'         => true,
    'single_blank_line_before_namespace' => true,
    'no_leading_namespace_whitespace'    => true,

    // ------------------------------------------------------------------- language constructs

    'is_null'                      => true,
    'dir_constant'                 => true,
    'single_line_throw'            => true,
    'no_useless_sprintf'           => true,
    'regular_callable_call'        => true,
    'combine_consecutive_issets'   => true,
    'combine_consecutive_unsets'   => true,
    'explicit_indirect_variable'   => true,

    'return_type_declaration'      => array('space_before' => 'none'),
    'declare_equal_normalize'      => array('space' => 'none'),

    'function_to_constant'         => array(
        'functions' => array(
            'get_called_class',
            'get_class',
            'get_class_this',
            'php_sapi_name',
            'phpversion',
            'pi',
        ),
    ),

    'class_keyword_remove' => false,

    'single_space_after_construct' => array(
        'constructs' => array(
            'abstract', 'as', 'attribute', 'break', 'case', 'catch', 'class', 'clone', 'comment', 'const', 'const_import', 'continue', 'do', 'echo', 'else', 'elseif', 'extends', 'final', 'finally', 'for', 'foreach', 'function', 'function_import', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'match', 'named_argument', 'new', 'open_tag_with_echo', 'php_doc', 'php_open', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'throw', 'trait', 'try', 'use', 'use_lambda', 'use_trait', 'var', 'while', 'yield', 'yield_from',
        ),
    ),

    // ------------------------------------------------------------------- functions

    'fopen_flag_order' => true,
    'fopen_flags'      => array('b_mode' => false),

    'function_typehint_space' => true,
    'lambda_not_used_import'  => true,
    'combine_nested_dirname'  => true,
    'function_declaration'    => array('closure_function_spacing' => 'one'),

    'no_spaces_after_function_name'         => true,
    'no_unreachable_default_argument_value' => true,

    'native_function_invocation'            => array(
        'include' => array('@all'),
        'scope'   => 'all',
        'strict'  => true,
    ),

    'method_argument_space' => array(
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline'                     => 'ensure_fully_multiline',
    ),

    // ------------------------------------------------------------------- import

    'no_unused_imports'           => true,
    'no_leading_import_slash'     => true,
    'single_line_after_imports'   => true,
    'single_import_per_statement' => true,

    // 'global_namespace_import'     => array(
    //     'import_classes'   => true,
    //     'import_constants' => true,
    //     'import_functions' => true,
    // ),

    // ------------------------------------------------------------------- basics

    'encoding' => true,

    'list_syntax'  => array('syntax' => 'long'),
    'array_syntax' => array('syntax' => 'long'),

    'braces' => array(
        'allow_single_line_closure'                         => true,
        'position_after_control_structures'                 => 'next',
        'position_after_anonymous_constructs'               => 'next',
        'position_after_functions_and_oop_constructs'       => 'next',
        'allow_single_line_anonymous_class_with_empty_body' => true,
    ),

    // ------------------------------------------------------------------- letter casing

    'lowercase_static_reference' => true,
    'magic_constant_casing'      => true,
    'magic_method_casing'        => true,
    'native_function_casing'     => true,
    'lowercase_keywords'         => true,
    'constant_case'              => array('case' => 'lower'),

    'native_function_type_declaration_casing' => true,

    // ------------------------------------------------------------------- cast notation

    'lowercase_cast'          => true,
    'no_unset_cast'           => true,
    'no_short_bool_cast'      => true, // || > (bool)
    'short_scalar_cast'       => true,
    'modernize_types_casting' => true,
    'cast_spaces'             => array('space' => 'single'),

    // ------------------------------------------------------------------- class related

    'final_class'          => true,
    'self_accessor'        => true,
    'self_static_accessor' => false, // we're using late binding in code in some places

    'no_php4_constructor'                => true,
    'no_unneeded_final_method'           => true,
    'no_null_property_initialization'    => true,
    'no_blank_lines_after_class_opening' => true,

    'protected_to_private'                    => true,
    'single_trait_insert_per_statement'       => true,
    'final_public_method_for_abstract_class'  => true,
    'visibility_required'                     => array('elements' => array('property', 'method')),
    'single_class_element_per_statement'      => array('elements' => array('property', 'const')),

    'class_definition'            => array('multi_line_extends_each_single_line' => true),
    'class_attributes_separation' => array('elements' => array('const' => 'one', 'method' => 'one', 'property' => 'one')),

    'ordered_class_elements' => array(
        'order' => array(
            'use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'phpunit', 'method_public', 'method_protected', 'method_private',
        ),
        'sort_algorithm' => 'alpha',
    ),

    // ------------------------------------------------------------------- control constructs

    'elseif'                          => true,
    'include'                         => true,
    'no_useless_else'                 => true,
    'simplified_if_return'            => true,
    'switch_case_space'               => true,
    'switch_continue_to_break'        => true,
    'switch_case_semicolon_to_colon'  => true,
    'no_superfluous_elseif'           => true,
    'no_alternative_syntax'           => true,
    'no_trailing_comma_in_list_call'  => true,

    'no_unneeded_curly_braces'        => array('namespaces' => true),
    'no_break_comment'                => array('comment_text' => 'no break comment here'),

    'no_unneeded_control_parentheses' => array(
        'statements' => array('break', 'clone', 'continue', 'echo_print', 'return', 'switch_case', 'yield'),
    ),

    // ------------------------------------------------------------------- alias

    'mb_str_functions'       => false,
    'backtick_to_shell_exec' => true,
    'pow_to_exponentiation'  => true,
    'ereg_to_preg'           => true,
    'set_type_to_cast'       => true,
    'random_api_migration'   => array('replacements' => array('mt_rand' => 'random_int', 'rand' => 'random_int')),
    'no_mixed_echo_print'    => array('use' => 'echo'),

    'no_alias_language_construct_call' => true,

    // ------------------------------------------------------------------- arrays

    'trim_array_spaces'                           => true,
    'whitespace_after_comma_in_array'             => true,
    'normalize_index_brace'                       => true,
    'trailing_comma_in_multiline_array'           => true,
    'no_trailing_comma_in_singleline_array'       => true,
    'no_whitespace_before_comma_in_array'         => true,
    'no_multiline_whitespace_around_double_arrow' => true,

    // ------------------------------------------------------------------- operators

    'new_with_braces'            => true,
    'logical_operators'          => true,
    'unary_operator_spaces'      => true,
    'standardize_not_equals'     => true,
    'ternary_to_null_coalescing' => true,
    'ternary_operator_spaces'    => true,

    'not_operator_with_space'           => true,
    'not_operator_with_successor_space' => true,

    'concat_space'       => array('spacing' => 'one'),
    'increment_style'    => array('style' => 'post'),
    'operator_linebreak' => array('position' => 'beginning', 'only_booleans' => false),

    'binary_operator_spaces' => array(
        'default'   => 'single_space',
        'operators' => array(
            '=>' => 'align_single_space',
        ),
    ),

    // ------------------------------------------------------------------- php tags

    'no_closing_tag'   => true,
    'full_opening_tag' => true,
    'echo_tag_syntax'  => array('format' => 'long', 'shorten_simple_statements_only' => false),

    'blank_line_after_opening_tag' => true,
    'linebreak_after_opening_tag'  => true,

    // ------------------------------------------------------------------- return

    'no_useless_return'      => true,
    'return_assignment'      => true,
    'simplified_null_return' => true,

    // ------------------------------------------------------------------- semicolon

    'no_empty_statement'                         => true,
    'semicolon_after_instruction'                => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'space_after_semicolon'                      => array('remove_in_empty_for_expressions' => true),
    'multiline_whitespace_before_semicolons'     => array('strategy' => 'no_multi_line'),
    // 'multiline_whitespace_before_semicolons'     => array('strategy' => 'new_line_for_chained_calls'),

    // ------------------------------------------------------------------- string

    'single_quote'                      => array('strings_containing_single_quote_chars' => true),
    'no_binary_string'                  => true,
    'no_trailing_whitespace_in_string'  => true,
    'explicit_string_variable'          => true,
    'simple_to_complex_string_variable' => true,

    // ------------------------------------------------------------------- whitespace
    'line_ending'                  => true,
    'indentation_type'             => true,
    'array_indentation'            => true,
    'method_chaining_indentation'  => true,
    'no_spaces_inside_parenthesis' => true,
    'no_trailing_whitespace'       => true,
    'no_whitespace_in_blank_line'  => true,
    'single_blank_line_at_eof'     => true,

    'no_extra_blank_lines' => array(
        'tokens' => array(
            'case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait',
        ),
    ),

    'no_extra_consecutive_blank_lines' => array(
        'tokens' => array(
            'case', 'continue', 'curly_brace_block', 'default', 'extra', 'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait',
        ),
    ),

    'blank_line_before_statement' => array(
        'statements' => array(
            'break', 'yield', 'case', 'continue', 'declare', 'default', 'exit', 'goto', 'include', 'include_once', 'require', 'require_once', 'return', 'switch', 'throw', 'try',
        ),
    ),

    'native_constant_invocation' => array('fix_built_in' => false, 'include' => array('MY_CUSTOM_PI')),
);

$finder = Finder::create()
    ->in(
        array(
            __DIR__ . '/-admin',
            __DIR__ . '/Inc',
            __DIR__ . '/init',
            __DIR__ . '/lib',
            __DIR__ . '/profiles',
            __DIR__ . '/themes',
        )
    )
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Config::create()
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
