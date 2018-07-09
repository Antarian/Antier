<?php
namespace App\Model;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(typeProperty="type", mapping={
 *    "text"="App\Model\BlogContentTextModel",
 *    "code"="App\Model\BlogContentCodeModel"
 * })
 */
interface BlogContentInterface
{
    /**
     * @return string
     */
    public function getType(): string;
}
