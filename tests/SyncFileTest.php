<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SupportPal\LanguageTools\IO\Sync\SyncFile;

class SyncFileTest extends TestCase
{
    public function testFileNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SyncFile('foo', 'bar');
    }

    /** @dataProvider syncProvider */
    public function testSync(string $file1, string $file2, string $expected): void
    {
        $this->assertSame(
            $expected,
            (new SyncFile($file1, $file2))->sync()->getContents()
        );
    }

    /**
     * @return mixed[]
     */
    public function syncProvider(): iterable
    {
        $base = __DIR__.'/__fixtures__/fail';

        yield [
            $base . '/en/comments.php',
            $base . '/es/comments.php',
            '<?php declare(strict_types=1);

return [
    "foo1" => "foo", // foo bar
];
'
        ];

        yield [
            $base . '/en/mixed-quotes.php',
            $base . '/es/mixed-quotes.php',
            '<?php declare(strict_types=1);

return [
    "foo1_bar" => \'foo bar\',
];
'
        ];
    }
}
