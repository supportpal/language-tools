<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Untranslated;

use Illuminate\Support\Arr;
use SupportPal\LanguageTools\IO\File as IOFile;

use function count;

class File extends IOFile
{
    public function totalLanguageStrings(): int
    {
        return count($this->flatten($this->file2));
    }

    /**
     * @return LanguageString[]
     */
    public function find(): array
    {
        $results = [];
        $base = $this->flatten($this->file1);
        foreach ($this->flatten($this->file2) as $key => $value) {
            $baseValue = Arr::get($base, $key);
            if ($baseValue !== $value || empty($value)) {
                continue;
            }

            $results[] = new LanguageString($key, $value);
        }

        return $results;
    }

    /**
     * @return mixed[]
     */
    private function flatten(string $path): array
    {
        return Arr::dot(require $path);
    }
}
