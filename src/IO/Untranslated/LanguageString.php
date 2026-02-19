<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Untranslated;

use Stringable;

use function sprintf;

class LanguageString implements Stringable
{
    /** @var string */
    private $key;

    /** @var string */
    private $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return sprintf('"%s" => "%s",', $this->key, $this->value);
    }
}
