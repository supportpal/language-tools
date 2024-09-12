<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Untranslated;

use SplFileInfo;
use SupportPal\LanguageTools\IO\Directory as IODirectory;

use function count;

class Directory extends IODirectory
{
    /**
     * @return array<string, FileStatistics>
     */
    public function all(): array
    {
        $results = [];
        $this->each(function (SplFileInfo $file, SplFileInfo $otherPath) use (&$results) {
            $untranslatedFile = new File($file->getPathname(), $otherPath->getPathname());
            $untranslatedStrings = $untranslatedFile->find();
            if (count($untranslatedStrings) === 0) {
                return null;
            }

            $results[$file->getFilename()] = new FileStatistics(
                $untranslatedFile->totalLanguageStrings(),
                $untranslatedStrings
            );
        });

        return $results;
    }
}
