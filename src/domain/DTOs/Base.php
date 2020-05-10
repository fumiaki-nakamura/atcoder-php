<?php
namespace Domain\DTOs;

/**
 * Class Base
 * @package Domain\DTOs
 */
abstract class Base
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->$name;
    }
}
