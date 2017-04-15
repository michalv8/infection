<?php

declare(strict_types=1);


namespace Infection\Mutant;


use Infection\Mutation;

class Mutant
{
    private $mutatedFilePath;
    /**
     * @var Mutation
     */
    private $mutation;
    /**
     * @var string
     */
    private $diff;

    public function __construct(string $mutatedFilePath, Mutation $mutation, string $diff)
    {
        $this->mutatedFilePath = $mutatedFilePath;
        $this->mutation = $mutation;
        $this->diff = $diff;
    }

    /**
     * @return string
     */
    public function getMutatedFilePath(): string
    {
        return $this->mutatedFilePath;
    }

    /**
     * @return Mutation
     */
    public function getMutation(): Mutation
    {
        return $this->mutation;
    }

    public function getDiff() : string
    {
        return $this->diff;
    }
}