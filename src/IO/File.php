<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO;

use RuntimeException;

use function file_get_contents;
use function sprintf;

class File
{
    /** @var string */
    protected $file1;

    /** @var string */
    protected $file2;

    public function __construct(string $file1, string $file2)
    {
        $this->validate($file1, $file2);

        $this->file1 = $file1;
        $this->file2 = $file2;
    }

    protected function validate(string $file1, string $file2): bool
    {
        return true;
    }

    protected function getFileContents(string $path): string
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new RuntimeException(sprintf('Failed to read contents of %s.', $path));
        }

        return $contents;
    }
}
