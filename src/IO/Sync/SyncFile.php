<?php declare(strict_types=1);

namespace SupportPal\LanguageTools\IO\Sync;

use InvalidArgumentException;
use RuntimeException;
use SupportPal\LanguageTools\IO\File;

use function addcslashes;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_array;
use function preg_quote;
use function preg_replace_callback;
use function sprintf;
use function str_replace;
use function substr;
use function uniqid;

class SyncFile extends File
{
    /** @var string */
    private $contents;

    /** @var string */
    private $uniqId;

    public function sync(): self
    {
        $contents = file_get_contents($this->file1);
        if ($contents === false) {
            throw new RuntimeException('Failed to read file contents.');
        }

        $this->uniqId = uniqid('__');
        $this->contents = $contents;
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
        $contents = preg_replace_callback(
            $this->getRegex($key),
            function (array $matches) use ($key, $value) {
                $usingDoubleQuotes = substr($matches[1], -1) === '"';

                return str_replace($key, $key . $this->uniqId, $matches[1])
                    . $this->mapValue($value, $usingDoubleQuotes)
                    . $matches[4];
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
