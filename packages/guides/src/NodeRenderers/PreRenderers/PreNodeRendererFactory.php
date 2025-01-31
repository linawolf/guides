<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\NodeRenderers\PreRenderers;

use phpDocumentor\Guides\NodeRenderers\NodeRenderer;
use phpDocumentor\Guides\NodeRenderers\NodeRendererFactory;
use phpDocumentor\Guides\Nodes\Node;

use function count;

/**
 * Decorator to add pre-rendering logic to node renderers.
 */
final class PreNodeRendererFactory implements NodeRendererFactory
{
    public function __construct(
        private NodeRendererFactory $innerFactory,
        /** @var iterable<PreNodeRenderer> */
        private iterable $preRenderers,
    ) {
    }

    public function get(Node $node): NodeRenderer
    {
        $preRenderers = [];
        foreach ($this->preRenderers as $preRenderer) {
            if (!$preRenderer->supports($node)) {
                continue;
            }

            $preRenderers[] = $preRenderer;
        }

        if (count($preRenderers) === 0) {
            return $this->innerFactory->get($node);
        }

        return new PreRenderer($this->innerFactory->get($node), $preRenderers);
    }
}
