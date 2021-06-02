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
        $base = __DIR__.'/__fixtures__/sync';

        yield [
            $base . '/comment-quotes.php',
            $base . '/comment-quotes.php',
            '<?php declare(strict_types=1);

return [
    "foo"           => "times", // As in \'5 times\'
];
'
        ];

        yield [
            $base . '/escaped-quotes.php',
            $base . '/escaped-quotes.php',
            '<?php declare(strict_types=1);

return [
    "foo"         => "foo \\"admin\\"",
];
'
        ];

        yield [
            $base . '/linefeeds.php',
            $base . '/linefeeds.php',
            '<?php declare(strict_types=1);

return [
    "foo"         => "foo\nbar",
];
'
        ];

        yield [
            $base . '/mixed-quotes.php',
            $base . '/mixed-quotes.php',
            '<?php declare(strict_types=1);

return [
    "foo"           => "it\'s time",
];
'
        ];
    }
}
