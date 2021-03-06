<?php

namespace Jungi\FrameworkExtraBundle\Annotation;

use Jungi\FrameworkExtraBundle\Attribute\RequestParam as BaseRequestParam;

/**
 * @author Piotr Kugla <piku235@gmail.com>
 *
 * @Annotation
 * @Target({"METHOD"})
 */
final class RequestParam extends BaseRequestParam implements Argument
{
    use StatefulTrait;

    private $argument;

    public function __construct(array $data)
    {
        parent::__construct($data['name'] ?? $data['value'] ?? null);

        $this->argument = $data['argument'] ?? $data['value'] ?? null;
    }

    public function argument(): string
    {
        return $this->argument;
    }
}
