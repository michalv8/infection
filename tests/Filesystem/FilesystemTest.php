<?php
/**
 * Copyright © 2017 Maks Rafalko
 *
 * License: https://opensource.org/licenses/BSD-3-Clause New BSD License
 */
declare(strict_types=1);

namespace Infection\Tests\Filesystem;

use Infection\Filesystem\Exception\IOException;
use Infection\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $workspace;

    private $umask;

    protected function setUp()
    {
        $this->umask = umask(0);
        $this->filesystem = new Filesystem();
        $this->workspace = sys_get_temp_dir() . DIRECTORY_SEPARATOR . microtime(true) . random_int(100, 999);
        mkdir($this->workspace, 0777, true);
    }

    protected function tearDown()
    {
        @unlink($this->workspace);
        umask($this->umask);
    }

    public function test_mkdir_creates_directory()
    {
        $dir = $this->workspace . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;

        $this->filesystem->mkdir($dir);

        $this->assertTrue(is_dir($dir));
    }

    public function test_mkdir_creates_directory_recursively()
    {
        $dir = $this->workspace
            . DIRECTORY_SEPARATOR . 'test'
            . DIRECTORY_SEPARATOR . 'sub_directory';

        $this->filesystem->mkdir($dir);

        $this->assertTrue(is_dir($dir));
    }

    /**
     * @expectedException \Infection\Filesystem\Exception\IOException
     * @expectedExceptionCode 0
     */
    public function test_mkdir_creates_directory_fails()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $dir = $basePath.'2';

        file_put_contents($dir, '');

        $this->filesystem->mkdir($dir);
    }

    public function test_mkdir_passes_path_to_io_exceptino()
    {
        $basePath = $this->workspace.DIRECTORY_SEPARATOR;
        $dir = $basePath.'2';

        file_put_contents($dir, '');

        try {
            $this->filesystem->mkdir($dir);
        } catch (IOException $e) {
            $this->assertSame($dir, $e->getPath());
        }
    }

    public function test_mkdir_does_not_fail_when_dir_already_exists()
    {
        $dir = $this->workspace . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR;

        $this->filesystem->mkdir($dir);
        $this->filesystem->mkdir($dir);

        $this->assertTrue(is_dir($dir));
    }

    public function test_dump_file_creates_file_with_content()
    {
        $filename = $this->workspace . DIRECTORY_SEPARATOR . 'foo' . DIRECTORY_SEPARATOR . 'baz.txt';

        $this->filesystem->dumpFile($filename, 'bar');
        $this->assertFileExists($filename);
        $this->assertSame('bar', file_get_contents($filename));
    }

    public function test_dump_file_overwrites_an_existing_file()
    {
        $filename = $this->workspace . DIRECTORY_SEPARATOR . 'foo.txt';
        file_put_contents($filename, 'FOO BAR');

        $this->filesystem->dumpFile($filename, 'bar');

        $this->assertFileExists($filename);
        $this->assertSame('bar', file_get_contents($filename));
    }
}
