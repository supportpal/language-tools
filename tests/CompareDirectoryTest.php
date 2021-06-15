<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use SupportPal\LanguageTools\IO\Compare\CompareDirectory;

use function count;

class CompareDirectoryTest extends TestCase
{
    public function testSuccess(): void
    {
        $base = __DIR__.'/__fixtures__/success';
        $comparison = new CompareDirectory($base.'/en', $base.'/es');

        $this->assertCount(0, $comparison->diff());
    }

    public function testFail(): void
    {
        $base = __DIR__.'/__fixtures__/fail';
        $comparison = new CompareDirectory($base.'/en', $base.'/es');

        $this->assertGreaterThan(0, count($comparison->diff()));
    }
}
