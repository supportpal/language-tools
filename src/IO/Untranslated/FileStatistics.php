<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Untranslated;

use function count;

class FileStatistics
{
    /** @var int */
    private $total;

    /** @var mixed[] */
    private $untranslated;

    /**
     * @param mixed[] $untranslated
     */
    public function __construct(int $total, array $untranslated)
    {
        $this->total = $total;
        $this->untranslated = $untranslated;
    }

    public function totalStrings(): int
    {
        return $this->total;
    }

    public function totalUntranslated(): int
    {
        return count($this->untranslated);
    }

    /**
     * @return mixed[]
     */
    public function untranslated(): array
    {
        return $this->untranslated;
    }
}
