<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.8.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();

return $config->setRiskyAllowed(true)->setRules([
    // Each element of an array must be indented exactly once.
    'array_indentation' => true,
    // PHP arrays should be declared using the configured syntax.
    'array_syntax' => ['syntax' => 'short'],
    // Use the null coalescing assignment operator `??=` where possible.
    'assign_null_coalescing_to_coalesce_equal' => true,
    // Binary operators should be surrounded by space as configured.
    'binary_operator_spaces' => true,
    // There MUST be one blank line after the namespace declaration.
    'blank_line_after_namespace' => true,
    // Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
    'blank_line_after_opening_tag' => true,
    // An empty line feed must precede any configured statement.
    'blank_line_before_statement' => true,
    // The body of each structure MUST be enclosed by braces. Braces should be properly placed. Body of braces should be properly indented.
    'braces' => ['allow_single_line_closure' => true],
    // A single space or none should be between cast and variable.
    'cast_spaces' => ['space' => 'none'],
    // Class, trait and interface elements must be separated with one or none blank line.
    'class_attributes_separation' => true,
    // Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.
    'class_definition' => ['inline_constructor_arguments' => false, 'space_before_parenthesis' => true],
    // When referencing an internal class it must be written using the correct casing.
    'class_reference_name_casing' => true,
    // Namespace must not contain spacing, comments or PHPDoc.
    'clean_namespace' => true,
    // Using `isset($var) &&` multiple times should be done in one call.
    'combine_consecutive_issets' => true,
    // Calling `unset` on multiple items should be done in one call.
    'combine_consecutive_unsets' => true,
    // Remove extra spaces in a nullable typehint.
    'compact_nullable_typehint' => true,
    // Concatenation should be spaced according configuration.
    'concat_space' => ['spacing' => 'one'],
    // The PHP constants `true`, `false`, and `null` MUST be written using the correct casing.
    'constant_case' => true,
    // Equal sign in declare statement should be surrounded by spaces or not following configuration.
    'declare_equal_normalize' => true,
    // The keyword `elseif` should be used instead of `else if` so that all control keywords look like single words.
    'elseif' => true,
    // Empty loop-body must be in configured style.
    'empty_loop_body' => true,
    // Empty loop-condition must be in configured style.
    'empty_loop_condition' => true,
    // PHP code MUST use only UTF-8 without BOM (remove BOM).
    'encoding' => true,
    // Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
    'explicit_indirect_variable' => true,
    // PHP code must use the long `<?php` tags or short-echo `<?=` tags and not other tag variations.
    'full_opening_tag' => true,
    // Transforms imported FQCN parameters and return types in function arguments to short version.
    'fully_qualified_strict_types' => true,
    // Spaces should be properly placed in a function declaration.
    'function_declaration' => true,
    // Ensure single space between function's argument and its typehint.
    'function_typehint_space' => true,
    // Imports or fully qualifies global classes/functions/constants.
    'global_namespace_import' => ['import_classes' => true],
    // There MUST be group use for the same namespaces.
    'group_import' => true,
    // Include/Require and file path should be divided with a single space. File path should not be placed under brackets.
    'include' => true,
    // Pre- or post-increment and decrement operators should be used if possible.
    'increment_style' => ['style' => 'post'],
    // Code MUST use configured indentation type.
    'indentation_type' => true,
    // Lambda must not import variables it doesn't use.
    'lambda_not_used_import' => true,
    // All PHP files must use same line ending.
    'line_ending' => true,
    // Ensure there is no code on the same line as the PHP open tag.
    'linebreak_after_opening_tag' => true,
    // Cast should be written in lower case.
    'lowercase_cast' => true,
    // PHP keywords MUST be in lower case.
    'lowercase_keywords' => true,
    // Class static references `self`, `static` and `parent` MUST be in lower case.
    'lowercase_static_reference' => true,
    // In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma. Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.
    'method_argument_space' => ['on_multiline' => 'ensure_single_line'],
    // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
    'multiline_whitespace_before_semicolons' => true,
    // Function defined by PHP should be called using the correct casing.
    'native_function_casing' => true,
    // All instances created with `new` keyword must (not) be followed by braces.
    'new_with_braces' => true,
    // Master language constructs shall be used instead of aliases.
    'no_alias_language_construct_call' => true,
    // Replace control structure alternative syntax to use braces.
    'no_alternative_syntax' => true,
    // There should be no empty lines after class opening brace.
    'no_blank_lines_after_class_opening' => true,
    // There should not be blank lines between docblock and the documented element.
    'no_blank_lines_after_phpdoc' => true,
    // There must be a comment when fall-through is intentional in a non-empty case body.
    'no_break_comment' => true,
    // The closing `? >` tag MUST be omitted from files containing only PHP.
    'no_closing_tag' => true,
    // There should not be any empty comments.
    'no_empty_comment' => true,
    // There should not be empty PHPDoc blocks.
    'no_empty_phpdoc' => true,
    // Remove useless (semicolon) statements.
    'no_empty_statement' => true,
    // Removes extra blank lines and/or blank lines following configuration.
    'no_extra_blank_lines' => ['tokens' => ['break', 'case', 'continue', 'curly_brace_block', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait']],
    // Remove leading slashes in `use` clauses.
    'no_leading_import_slash' => true,
    // The namespace declaration line shouldn't contain leading whitespace.
    'no_leading_namespace_whitespace' => true,
    // Operator `=>` should not be surrounded by multi-line whitespaces.
    'no_multiline_whitespace_around_double_arrow' => true,
    // Properties MUST not be explicitly initialized with `null` except when they have a type declaration (PHP 7.4).
    'no_null_property_initialization' => true,
    // Short cast `bool` using double exclamation mark should not be used.
    'no_short_bool_cast' => true,
    // Single-line whitespace before closing semicolon are prohibited.
    'no_singleline_whitespace_before_semicolons' => true,
    // There must be no space around double colons (also called Scope Resolution Operator or Paamayim Nekudotayim).
    'no_space_around_double_colon' => true,
    // When making a method or function call, there MUST NOT be a space between the method or function name and the opening parenthesis.
    'no_spaces_after_function_name' => true,
    // There MUST NOT be spaces around offset braces.
    'no_spaces_around_offset' => true,
    // There MUST NOT be a space after the opening parenthesis. There MUST NOT be a space before the closing parenthesis.
    'no_spaces_inside_parenthesis' => true,
    // Replaces superfluous `elseif` with `if`.
    'no_superfluous_elseif' => true,
    // Remove trailing commas in list function calls.
    'no_trailing_comma_in_list_call' => true,
    // When making a method or function call on a single line there MUST NOT be a trailing comma after the last argument.
    'no_trailing_comma_in_singleline_function_call' => true,
    // Remove trailing whitespace at the end of non-blank lines.
    'no_trailing_whitespace' => true,
    // There MUST be no trailing spaces inside comment or PHPDoc.
    'no_trailing_whitespace_in_comment' => true,
    // Variables must be set `null` instead of using `(unset)` casting.
    'no_unset_cast' => true,
    // Unused `use` statements must be removed.
    'no_unused_imports' => true,
    // There should not be useless `else` cases.
    'no_useless_else' => true,
    // There should not be an empty `return` statement at the end of a function.
    'no_useless_return' => true,
    // In array declaration, there MUST NOT be a whitespace before each comma.
    'no_whitespace_before_comma_in_array' => true,
    // Remove trailing whitespace at the end of blank lines.
    'no_whitespace_in_blank_line' => true,
    // Array index should always be written by using square braces.
    'normalize_index_brace' => true,
    // Adds or removes `?` before type declarations for parameters with a default `null` value.
    'nullable_type_declaration_for_default_null_value' => true,
    // There should not be space before or after object operators `->` and `?->`.
    'object_operator_without_whitespace' => true,
    // Orders the elements of classes/interfaces/traits/enums.
    'ordered_class_elements' => ['order' => ['use_trait']],
    // Ordering `use` statements.
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    // Orders the interfaces in an `implements` or `interface extends` clause.
    'ordered_interfaces' => true,
    // Trait `use` statements must be sorted alphabetically.
    'ordered_traits' => true,
    // Annotations in PHPDoc should be ordered so that `@param` annotations come first, then `@throws` annotations, then `@return` annotations.
    'phpdoc_order' => true,
    // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.
    'phpdoc_separation' => true,
    // Single line `@var` PHPDoc should have proper spacing.
    'phpdoc_single_line_var_spacing' => true,
    // Fixes casing of PHPDoc tags.
    'phpdoc_tag_casing' => true,
    // Removes extra blank lines after summary and after description in PHPDoc.
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    // The correct case must be used for standard PHP types in PHPDoc.
    'phpdoc_types' => true,
    // `@var` and `@type` annotations must have type and name in the correct order.
    'phpdoc_var_annotation_correct_order' => true,
    // Local, dynamic and directly referenced variables should not be assigned and directly returned by a function or method.
    'return_assignment' => true,
    // There should be one or no space before colon, and one space after it in return type declarations, according to configuration.
    'return_type_declaration' => true,
    // Instructions must be terminated with a semicolon.
    'semicolon_after_instruction' => true,
    // Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.
    'short_scalar_cast' => true,
    // A PHP file without end tag must always end with a single empty line feed.
    'single_blank_line_at_eof' => true,
    // There should be exactly one blank line before a namespace declaration.
    'single_blank_line_before_namespace' => true,
    // There MUST NOT be more than one property or constant declared per statement.
    'single_class_element_per_statement' => ['elements' => ['property']],
    // There MUST be one use keyword per declaration.
    // 'single_import_per_statement' => true,
    // Each namespace use MUST go on its own line and there MUST be one blank line after the use statements block.
    'single_line_after_imports' => true,
    // Single-line comments must have proper spacing.
    'single_line_comment_spacing' => true,
    // Single-line comments and multi-line comments with only one line of actual content should use the `//` syntax.
    'single_line_comment_style' => ['comment_types' => []],
    // Convert double quotes to single quotes for simple strings.
    'single_quote' => true,
    // Ensures a single space after language constructs.
    'single_space_after_construct' => true,
    // Each trait `use` must be done as single statement.
    'single_trait_insert_per_statement' => true,
    // Fix whitespace after a semicolon.
    'space_after_semicolon' => true,
    // Increment and decrement operators should be used if possible.
    'standardize_increment' => true,
    // Replace all `<>` with `!=`.
    'standardize_not_equals' => true,
    // A case should be followed by a colon and not a semicolon.
    'switch_case_semicolon_to_colon' => true,
    // Removes extra spaces between colon and case value.
    'switch_case_space' => true,
    // Standardize spaces around ternary operator.
    'ternary_operator_spaces' => true,
    // Use `null` coalescing operator `??` where possible. Requires PHP >= 7.0.
    'ternary_to_null_coalescing' => true,
    // Multi-line arrays, arguments list and parameters list must have a trailing comma.
    'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    // Arrays should be formatted like function/method arguments, without leading or trailing single line space.
    'trim_array_spaces' => true,
    // Unary operators should be placed adjacent to their operands.
    'unary_operator_spaces' => true,
    // Visibility MUST be declared on all properties and methods; `abstract` and `final` MUST be declared before the visibility; `static` MUST be declared after the visibility.
    'visibility_required' => true,
    // In array declaration, there MUST be a whitespace after each comma.
    'whitespace_after_comma_in_array' => true,
])->setFinder(PhpCsFixer\Finder::create()->exclude(['vendor', 'storage', 'bootstrap/cache'])->in(__DIR__));
