<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Guides\Nodes;

use phpDocumentor\Guides\Nodes\DefinitionLists\DefinitionListItemNode;

/**
 * @extends CompoundNode<DefinitionListItemNode>
 */
class DefinitionListNode extends CompoundNode
{
    public function __construct(DefinitionListItemNode ...$definitionListItems)
    {
        parent::__construct($definitionListItems);
    }
}
