<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\Parser\Productions\FieldList;

use phpDocumentor\Guides\Nodes\FieldLists\FieldListItemNode;
use phpDocumentor\Guides\Nodes\Metadata\MetadataNode;
use phpDocumentor\Guides\Nodes\Metadata\TocDepthNode;

use function strtolower;

class TocDepthFieldListItemRule implements FieldListItemRule
{
    public function applies(FieldListItemNode $fieldListItemNode): bool
    {
        return strtolower($fieldListItemNode->getTerm()) === 'tocdepth';
    }

    public function apply(FieldListItemNode $fieldListItemNode): MetadataNode
    {
        return new TocDepthNode((int) $fieldListItemNode->getPlaintextContent());
    }
}