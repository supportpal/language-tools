<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Compare;

use InvalidArgumentException;
use RuntimeException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use SupportPal\LanguageTools\IO\File;

use function file_get_contents;
use function is_string;
use function preg_replace;
use function sprintf;
use function strlen;
use function trim;

class CompareFile extends File
{
    /** @var array<mixed> */
    private $diff;

    /**
     * @return mixed[]
     */
    public function diffToArray(): array
    {
        if ($this->diff !== null) {
            return $this->diff;
        }

        return $this->diff = (new Differ)->diffToArray(
            $this->getContentsWithoutValues($this->file1),
            $this->getContentsWithoutValues($this->file2)
        );
    }

    public function hasDifferences(): bool
    {
        $diff = (new UnifiedDiffOutputBuilder(''))->getDiff($this->diffToArray());

        return strlen(trim($diff)) > 0;
    }

    public function diff(): string
    {
        return (new UnifiedDiffOutputBuilder)->getDiff(
            $this->diffToArray()
        );
    }

    private function getContentsWithoutValues(string $path): string
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new InvalidArgumentException(sprintf('Unable to read file: %s', $path));
        }

        $contents = preg_replace('/(=>\s([\'"])).*(\2)(,?.*)$/m', '$1$2$4', $contents);
        if (! is_string($contents)) {
            throw new RuntimeException('Failed to remove translations from file.');
        }

        return $contents;
    }
}
