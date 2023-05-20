<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\TextRoles;

use phpDocumentor\Guides\Nodes\InlineToken\EmphasisToken;
use phpDocumentor\Guides\Nodes\InlineToken\InlineMarkupToken;
use phpDocumentor\Guides\ParserContext;

class EmphasisTextRole implements TextRole
{
    final public const NAME = 'emphasis';

    public function getName(): string
    {
        return self::NAME;
    }

    /** @inheritDoc */
    public function getAliases(): array
    {
        return ['italic'];
    }

    public function processNode(
        ParserContext $parserContext,
        string $id,
        string $role,
        string $content,
    ): InlineMarkupToken {
        return new EmphasisToken($id, $content);
    }
}
