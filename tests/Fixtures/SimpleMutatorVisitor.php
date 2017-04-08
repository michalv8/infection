<?php

declare(strict_types=1);

namespace Infection\Tests\Fixtures;

use Infection\Mutator\Mutator;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class SimpleMutatorVisitor extends NodeVisitorAbstract
{
    /**
     * @var Mutator
     */
    private $mutator;

    public function __construct(Mutator $mutator)
    {
        $this->mutator = $mutator;
    }

    public function leaveNode(Node $node)
    {
        if ($this->mutator->shouldMutate($node)) {
            return $this->mutator->mutate($node);
        }
    }
}