<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\NodeRenderers\PreRenderers;

use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\RenderContext;

interface PreNodeRenderer
{
    public function supports(Node $node): bool;

    public function execute(Node $node, RenderContext $renderContext): Node;
}
