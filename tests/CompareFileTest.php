<?php declare(strict_types=1);

namespace Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SupportPal\LanguageTools\IO\Compare\CompareFile;

class CompareFileTest extends TestCase
{
    public function testFileNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new CompareFile('foo', 'bar');
    }

    /** @dataProvider successProvider */
    public function testSuccess(string $file1, string $file2): void
    {
        $comparison = new CompareFile($file1, $file2);

        $this->assertFalse($comparison->hasDifferences());
    }

    /**
     * @return array<int, string[]>
     */
    public function successProvider(): iterable
    {
        $base = __DIR__.'/__fixtures__/success';

        yield [$base.'/en/comments.php', $base.'/es/comments.php'];

        yield [$base.'/en/mixed-quotes.php', $base.'/es/mixed-quotes.php'];

        yield [$base.'/en/nested.php', $base.'/es/nested.php'];

        yield [$base.'/en/no-comma.php', $base.'/es/no-comma.php'];

        yield [$base.'/en/simple.php', $base.'/es/simple.php'];

        yield [$base.'/en/single-quotes.php', $base.'/es/single-quotes.php'];
    }

    /** @dataProvider failProvider */
    public function testFail(string $file1, string $file2): void
    {
        $comparison = new CompareFile($file1, $file2);

        $this->assertTrue($comparison->hasDifferences());
    }

    /**
     * @return array<int, string[]>
     */
    public function failProvider(): iterable
    {
        $base = __DIR__.'/__fixtures__/fail';

        yield [$base.'/en/comments.php', $base.'/es/comments.php'];

        yield [$base.'/en/mixed-quotes.php', $base.'/es/mixed-quotes.php'];

        yield [$base.'/en/nested.php', $base.'/es/nested.php'];

        yield [$base.'/en/no-comma.php', $base.'/es/no-comma.php'];

        yield [$base.'/en/simple.php', $base.'/es/simple.php'];

        yield [$base.'/en/single-quotes.php', $base.'/es/single-quotes.php'];
    }
}
