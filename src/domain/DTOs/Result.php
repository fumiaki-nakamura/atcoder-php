<?php
namespace Domain\DTOs;

/**
 * Class Result
 * @package Domain\DTOs
 * @property-read float elapsedTime
 * @property-read string message
 */
class Result extends Base
{
    /** @var float */
    protected $elapsedTime;

    /** @var string */
    protected $message;

    /**
     * Result constructor.
     * @param array $primitives
     */
    public function __construct(array $primitives)
    {
        $this->elapsedTime = (float)$primitives['elapsed_time'];
        $this->message     = (string)$primitives['message'];
    }

    /**
     * @param array $primitives
     * @return Result
     */
    public static function create(array $primitives): self
    {
        return new static($primitives);
    }
}
