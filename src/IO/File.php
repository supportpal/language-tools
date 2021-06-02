<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO;

use InvalidArgumentException;

use function file_exists;
use function sprintf;

class File
{
    /** @var string */
    protected $file1;

    /** @var string */
    protected $file2;

    public function __construct(string $file1, string $file2)
    {
        foreach ([$file1, $file2] as $file) {
            if (! file_exists($file)) {
                throw new InvalidArgumentException(sprintf('%s does not exist.', $file));
            }
        }

        $this->file1 = $file1;
        $this->file2 = $file2;
    }
}
