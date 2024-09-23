<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Sync;

use InvalidArgumentException;
use SupportPal\LanguageTools\IO\File;

use function addcslashes;
use function file_exists;
use function file_put_contents;
use function is_array;
use function preg_match_all;
use function preg_quote;
use function preg_replace_callback;
use function sprintf;
use function str_replace;
use function str_split;
use function substr;
use function substr_count;
use function uniqid;

use const PHP_EOL;
use const PREG_OFFSET_CAPTURE;

class SyncFile extends File
{
    /** @var string */
    private $contents;

    /** @var string */
    private $uniqId;

    public function sync(): self
    {
        $this->uniqId = uniqid('__');
        $this->contents = $this->getFileContents($this->file1);
        if (file_exists($this->file2)) {
            $this->replaceArray(require $this->file2);
        }

        return $this;
    }

    public function write(): void
    {
        file_put_contents($this->file2, $this->getContents());
    }

    public function getContents(): string
    {
        return str_replace($this->uniqId, '', $this->contents);
    }

    protected function validate(string $file1, string $file2): bool
    {
        if (! file_exists($file1)) {
            throw new InvalidArgumentException(sprintf('File 1 \'%s\' must exist.', $file1));
        }

        return parent::validate($file1, $file2);
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
        $lineNumberFile1 = $this->getLineNumber($this->contents, $key);
        $lineNumberFile2 = $this->getLineNumber($this->getFileContents($this->file2), $key);

        $contents = preg_replace_callback(
            $this->getRegex($key),
            function (array $matches) use ($key, $value, $lineNumberFile1, $lineNumberFile2) {
                // Use file 1's value.
                if ($lineNumberFile1 !== $lineNumberFile2) {
                    return str_replace($key, $key . $this->uniqId, $matches[1])
                        . $matches['value']
                        . $matches[5];
                }

                $usingDoubleQuotes = substr($matches[1], -1) === '"';

                // Use file 2's value.
                return str_replace($key, $key . $this->uniqId, $matches[1])
                    . $this->mapValue($value, $usingDoubleQuotes)
                    . $matches[5];
            },
            $this->contents,
            1
        );

        if ($contents === null) {
            return;
        }

        $this->contents = $contents;
    }

    private function getRegex(string $key): string
    {
        return sprintf(
            '/^(\s*(["\'])%s\2\s*=>\s*([\'"]))(?<value>(?:[^"\\\\]|\\\\.)*)(\3,?.*?)$/m',
            preg_quote($key, '/')
        );
    }

    private function mapValue(string $value, bool $usingDoubleQuotes): string
    {
        $escapedValue = addcslashes($value, $usingDoubleQuotes ? '"' : '\'');
        $escapedValue = str_replace("\n", '\n', $escapedValue);

        return $escapedValue;
    }

    /**
     * If a key is duplicated in the array, only the line number of the first matching is returned.
     */
    private function getLineNumber(string $contents, string $key): ?int
    {
        preg_match_all($this->getRegex($key), $contents, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $match) {
            if (! isset($match[1]) || $match[1] < 0) {
                continue;
            }

            $string = str_split($contents, $match[1]);
            if (! isset($string[0])) {
                continue;
            }

            return substr_count($string[0], PHP_EOL) + 1;
        }

        return null;
    }
}
