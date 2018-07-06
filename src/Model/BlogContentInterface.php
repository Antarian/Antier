<?php
namespace App\Model;

use MongoDB\BSON\Persistable;
use Symfony\Component\Validator\Constraints as Assert;

interface BlogContentInterface
{
    /**
     * @return string
     */
    public function getType();
}
