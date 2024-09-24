<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO;

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
}
