<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO;

use Closure;
use InvalidArgumentException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

use function file_exists;
use function realpath;
use function sprintf;

use const DIRECTORY_SEPARATOR;

class Directory
{
    /** @var string */
    protected $dir1;

    /** @var string */
    protected $dir2;

    public function __construct(string $dir1, string $dir2)
    {
        foreach ([$dir1, $dir2] as $dir) {
            if (! file_exists($dir)) {
                throw new InvalidArgumentException(sprintf('Directory %s does not exist.', $dir));
            }
        }

        $this->dir1 = $dir1;
        $this->dir2 = $dir2;
    }

    public function each(Closure $callback): void
    {
        foreach (Finder::create()->files()->depth(0)->name('*.php')->in($this->dir1) as $file) {
            $otherPath = realpath($this->dir2) . DIRECTORY_SEPARATOR . $file->getFilename();

            $callback->call($this, $file, new SplFileInfo($otherPath));
        }
    }
}
