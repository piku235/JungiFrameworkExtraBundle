<?php

namespace Jungi\FrameworkExtraBundle\Annotation;

/**
 * @author Piotr Kugla <piku235@gmail.com>
 *
 * @internal
 */
interface Argument extends Annotation
{
    public function argument(): string;
}
