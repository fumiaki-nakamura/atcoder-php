<?php
namespace Domain\VOs;

/**
 * Class Code
 * @package Domain\VOs
 */
class Code
{
    /** @var resource */
    private $resource;

    /**
     * Code constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->resource = tmpfile();

        fwrite($this->resource, $code);
    }

    public function __destruct()
    {
        fclose($this->resource);
    }

    /**
     * @param string $code
     * @return Code
     */
    public static function create(string $code): self
    {
        return new static($code);
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return stream_get_meta_data($this->resource)['uri'];
    }

    /**
     * @return bool
     */
    public function isPhp(): bool
    {
        $result = $this->lintPhp();

        return strpos($result, 'No syntax errors') === 0;
    }

    /**
     * @return string
     */
    public function lintPhp(): string
    {
        return `php -l {$this->getFilePath()}`;
    }
}
