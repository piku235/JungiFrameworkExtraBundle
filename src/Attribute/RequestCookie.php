<?php

namespace Jungi\FrameworkExtraBundle\Attribute;

use Attribute as PhpAttribute;

/**
 * @author Piotr Kugla <piku235@gmail.com>
 *
 * @final-public should be treated as final outside the library, extended only by the annotation
 */
#[PhpAttribute(PhpAttribute::TARGET_PARAMETER)]
class RequestCookie implements NamedValue
{
    private $name;

    public static function __set_state(array $data)
    {
        return new self($data['name'] ?? null);
    }

    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}
