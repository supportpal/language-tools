<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Compare;

use SplFileInfo;
use SupportPal\LanguageTools\IO\Directory;

class CompareDirectory extends Directory
{
    /**
     * @return string[]
     */
    public function diff(): array
    {
        $differences = [];
        $this->each(function (SplFileInfo $file, SplFileInfo $otherPath) use (&$differences) {
            $comparison = new CompareFile($file->getPathname(), $otherPath->getPathname());
            if (! $comparison->hasDifferences()) {
                return null;
            }

            $differences[$file->getFilename()] = $comparison->diff();
        });

        return $differences;
    }
}
