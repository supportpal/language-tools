<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Sync;

use SplFileInfo;
use SupportPal\LanguageTools\IO\Directory;

class SyncDirectory extends Directory
{
    public function sync(): void
    {
        $this->each(function (SplFileInfo $file, SplFileInfo $otherFile) {
            $file = new SyncFile($file->getPathname(), $otherFile->getPathname());
            $file->sync()->write();
        });
    }
}
