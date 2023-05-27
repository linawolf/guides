<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Compiler\NodeTransformers;

use phpDocumentor\Guides\Compiler\CompilerPass;
use phpDocumentor\Guides\Compiler\DocumentNodeTraverser;
use phpDocumentor\Guides\Nodes\DocumentNode;

use function array_filter;

/**
 * The TransformerPass is a special kind of CompilerPass that traverses all documents and
 * Calls the DocumentNodeTraverser for each.
 *
 * The TransformerPass cannot be injected as there must be one for each available priority of
 * NodeTransformer.
 */
final class TransformerPass implements CompilerPass
{
    public function __construct(
        private readonly DocumentNodeTraverser $documentNodeTraverser,
        private readonly int $priority,
    ) {
    }

    /** {@inheritDoc} */
    public function run(array $documents): array
    {
        foreach ($documents as $key => $document) {
            if (!($document instanceof DocumentNode)) {
                continue;
            }

            $documents[$key] = $this->documentNodeTraverser->traverse($document);
        }

        return array_filter($documents, static fn ($document): bool => $document instanceof DocumentNode);
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}