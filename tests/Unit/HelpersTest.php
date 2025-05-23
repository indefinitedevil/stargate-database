<?php

use Illuminate\Support\Str;

test('process_markdown handles plain text correctly', function () {
    $plainText = 'This is just plain text with no formatting.';
    $result = process_markdown($plainText);

    // Plain text should be wrapped in a paragraph
    expect($result)->toBe('<p>This is just plain text with no formatting.</p>');
});

test('process_markdown converts basic markdown syntax', function () {
    $markdownText = "# Heading\n\n**Bold text** and *italic text* with [a link](https://example.com)";
    $result = process_markdown($markdownText);

    // Check heading
    expect($result)->toContain('<h1>Heading</h1>');
    // Check bold and italic
    expect($result)->toContain('<strong>Bold text</strong>');
    expect($result)->toContain('<em>italic text</em>');
    // Check link
    expect($result)->toContain('<a href=\"https://example.com\">a link</a>');
});

test('process_markdown adds custom classes to unordered lists', function () {
    $listText = "- Item 1\n- Item 2\n- Item 3";
    $result = process_markdown($listText);

    // Check that the list has the custom classes
    expect($result)->toContain('<ul class=\"list-disc list-inside\">');
    expect($result)->toContain('<li>Item 1</li>');
    expect($result)->toContain('<li>Item 2</li>');
    expect($result)->toContain('<li>Item 3</li>');
});

test('process_markdown properly escapes HTML and script tags', function () {
    $xssAttempt = "Normal text with <script>alert('XSS')</script> and <b>bold HTML</b>";
    $result = process_markdown($xssAttempt);

    // Script tags should be escaped/encoded
    expect($result)->not->toContain('<script>alert(\'XSS\')</script>');
    expect($result)->toContain('&lt;script&gt;alert(&#039;XSS&#039;)&lt;/script&gt;');
    // HTML tags should also be escaped/encoded
    expect($result)->not->toContain('<b>bold HTML</b>');
    expect($result)->toContain('&lt;b&gt;bold HTML&lt;/b&gt;');
});

test('process_markdown handles empty and whitespace-only inputs gracefully', function () {
    $emptyResult = process_markdown('');
    expect($emptyResult)->toBe('');
    $whitespaceResult = process_markdown('   ');
    expect($whitespaceResult)->toBe('<p>   </p>');
});

test('process_markdown handles nested lists correctly', function () {
    $nestedList = "- Item 1\n  - Nested Item 1.1\n  - Nested Item 1.2\n- Item 2";
    $result = process_markdown($nestedList);

    // Both levels of lists should have the custom classes
    $matches = [];
    preg_match_all('/<ul class=\"list-disc list-inside\">/', $result, $matches);
    expect(count($matches[0]))->toBeGreaterThanOrEqual(2);

    expect($result)->toContain('<li>Item 1</li>');
    expect($result)->toContain('<li>Nested Item 1.1</li>');
});

test('process_markdown renders code blocks correctly', function () {
    $codeBlock = "```php\n\$var = 'Hello World';\necho \$var;\n```";
    $result = process_markdown($codeBlock);

    // Check for code block with language class
    expect($result)->toContain('<pre><code class=\"language-php\">');
    expect($result)->toContain("\$var = 'Hello World';");
});