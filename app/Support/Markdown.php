<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Renders editor-authored Markdown.
 *
 * Raw HTML in the source is stripped rather than passed through, so a
 * compromised or careless editor account cannot inject scripts into a
 * public page.
 */
final class Markdown
{
    private const OPTIONS = [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ];

    public static function toHtml(?string $markdown): HtmlString
    {
        if (blank($markdown)) {
            return new HtmlString('');
        }

        return new HtmlString(Str::markdown($markdown, self::OPTIONS));
    }

    public static function toPlainText(?string $markdown): string
    {
        if (blank($markdown)) {
            return '';
        }

        return trim(html_entity_decode(strip_tags(Str::markdown($markdown, self::OPTIONS))));
    }
}
