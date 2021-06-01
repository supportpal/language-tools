<?php declare(strict_types=1);

namespace SupportPal\LanguageTools;

use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

use function file_exists;
use function realpath;
use function sprintf;

use const DIRECTORY_SEPARATOR;

class CompareDirectory
{
    /** @var string */
    private $dir1;

    /** @var string */
    private $dir2;

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

    /**
     * @return string[]
     */
    public function diff(): array
    {
        $differences = [];
        foreach (Finder::create()->files()->name('*.php')->in($this->dir1) as $file) {
            $path = $file->getPathname();
            $otherPath = realpath($this->dir2) . DIRECTORY_SEPARATOR . $file->getFilename();

            $comparison = new CompareFile($path, $otherPath);
            if (! $comparison->hasDifferences()) {
                continue;
            }

            $differences[$file->getFilename()] = $comparison->diff();
        }

        return $differences;
    }
}
