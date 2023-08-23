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
        $syncFile = new SyncFile($file1, $file2);
        $syncFile->sync();

        $this->assertSame($expected, $syncFile->getContents());
    }

    /**
     * @return mixed[]
     */
    public function syncProvider(): iterable
    {
        $base = __DIR__.'/__fixtures__/sync';

        yield [
            $base . '/en/simple.php',
            $base . '/es/simple.php',
            '<?php declare(strict_types=1);

return [
    "foo" => "translated foo",
];
'
        ];

        // Copy en/exists to es/exists.php as it doesn't currently exist.
        yield [
            $base . '/en/exists.php',
            $base . '/es/exists.php',
            '<?php declare(strict_types=1);

return [
    "foo" => "translated foo",
];
'
        ];

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

        yield [
            $base . '/mixed-quotes2.php',
            $base . '/mixed-quotes2.php',
            '<?php declare(strict_types=1);

return [
    "na"  => \'N/A\',
    "foo" => "bar",
];
'
        ];

        yield [
            $base . '/nested.php',
            $base . '/nested.php',
            '<?php declare(strict_types=1);

return [
    "array" => "The :attribute must be an array.",
    "between" => [
        "array" => "The :attribute must have between :min and :max items.",
    ],
];
'
        ];
    }
}
