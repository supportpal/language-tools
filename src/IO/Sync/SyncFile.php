<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Sync;

use RuntimeException;
use SupportPal\LanguageTools\IO\File;

use function addcslashes;
use function file_get_contents;
use function file_put_contents;
use function is_array;
use function preg_quote;
use function preg_replace_callback;
use function sprintf;
use function str_replace;
use function substr;

class SyncFile extends File
{
    /** @var string */
    private $contents;

    public function sync(): self
    {
        $contents = file_get_contents($this->file1);
        if ($contents === false) {
            throw new RuntimeException('Failed to read file contents.');
        }

        $this->contents = $contents;
        $this->replaceArray(require $this->file2);

        return $this;
    }

    public function write(): void
    {
        file_put_contents($this->file2, $this->contents);
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * @param mixed[] $data
     */
    private function replaceArray(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->replaceArray($value);
            } else {
                $this->replaceValue($key, $value);
            }
        }
    }

    private function replaceValue(string $key, string $value): void
    {
        $contents = preg_replace_callback(
            $this->getRegex($key),
            function (array $matches) use ($value) {
                $usingDoubleQuotes = substr($matches[1], -1) === '"';

                return $matches[1] . $this->mapValue($value, $usingDoubleQuotes) . $matches[4];
            },
            $this->contents
        );

        if ($contents === null) {
            return;
        }

        $this->contents = $contents;
    }

    private function getRegex(string $key): string
    {
        return sprintf(
            '/^(\s*(["\'])%s\2\s*=>\s*([\'"]))(?:[^"\\\\]|\\\\.)*(\3,?.*?)$/m',
            preg_quote($key, '/')
        );
    }

    private function mapValue(string $value, bool $usingDoubleQuotes): string
    {
        $escapedValue = addcslashes($value, $usingDoubleQuotes ? '"' : '\'');
        $escapedValue = str_replace("\n", '\n', $escapedValue);

        return $escapedValue;
    }
}
