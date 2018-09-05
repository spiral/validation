<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests\Checkers;

use Spiral\Files\Files;
use Spiral\Files\FilesInterface;
use Spiral\Validation\Tests\BaseTest;
use Zend\Diactoros\UploadedFile;

class FileTest extends BaseTest
{
    private $files;

    public function setUp()
    {
        parent::setUp();

        $this->files = new Files();
        $this->container->bind(FilesInterface::class, $this->files);
    }

    public function testExists()
    {
        $this->assertFail('a', [], [
            'a' => ['file:exists']
        ]);

        $this->assertFail('a', [
            'a' => null
        ], [
            'a' => ['file:exists']
        ]);

        $this->assertFail('a', [
            'a' => []
        ], [
            'a' => ['file:exists']
        ]);

        $this->assertValid([
            'a' => __FILE__
        ], [
            'a' => ['file:exists']
        ]);
    }

    public function testFakeUpload()
    {
        $this->assertValid([
            'a' => ['tmp_name' => __FILE__]
        ], [
            'a' => ['file:exists']
        ]);

        $this->assertFail('a', [
            'a' => ['tmp_name' => __FILE__]
        ], [
            'a' => ['file:uploaded']
        ]);

        $this->assertValid([
            'a' => ['tmp_name' => __FILE__, 'uploaded' => true]
        ], [
            'a' => ['file:uploaded']
        ]);
    }

    public function testExistsStream()
    {
        $uploaded = new UploadedFile(__FILE__, filesize(__FILE__), 0);

        $this->assertValid([
            'a' => $uploaded
        ], [
            'a' => ['file:exists']
        ]);

        $uploaded = new UploadedFile(__FILE__, filesize(__FILE__), 1);

        $this->assertFail('a', [
            'a' => $uploaded
        ], [
            'a' => ['file:exists']
        ]);
    }

    public function testUploaded()
    {
        $this->assertFail('a', [], [
            'a' => ['file:uploaded']
        ]);

        $this->assertFail('a', [
            'a' => null
        ], [
            'a' => ['file:uploaded']
        ]);

        $this->assertFail('a', [
            'a' => []
        ], [
            'a' => ['file:uploaded']
        ]);

        $this->assertFail('a', [
            'a' => __FILE__
        ], [
            'a' => ['file:uploaded']
        ]);
    }

    public function testUploadedSteam()
    {
        $uploaded = new UploadedFile(__FILE__, filesize(__FILE__), 0);

        $this->assertValid([
            'a' => $uploaded
        ], [
            'a' => ['file:uploaded']
        ]);

        $uploaded = new UploadedFile(__FILE__, filesize(__FILE__), 1);

        $this->assertFail('a', [
            'a' => $uploaded
        ], [
            'a' => ['file:uploaded']
        ]);
    }

    public function testSize()
    {
        $this->assertFail('a', [], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1023)
        );

        clearstatcache();
        $this->assertValid([
            'a' => $tmpFile
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1024)
        );

        clearstatcache();
        $this->assertValid([
            'a' => $tmpFile
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1025)
        );

        clearstatcache();
        $this->assertFail('a', [
            'a' => $tmpFile
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);
    }

    public function testSizeStream()
    {
        $this->assertFail('a', [], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1023)
        );

        clearstatcache();
        $this->assertValid([
            'a' => new UploadedFile($tmpFile, filesize($tmpFile), 0)
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1024)
        );

        clearstatcache();
        $this->assertValid([
            'a' => new UploadedFile($tmpFile, filesize($tmpFile), 0)
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1025)
        );

        clearstatcache();
        $this->assertFail('a', [
            'a' => new UploadedFile($tmpFile, filesize($tmpFile), 0)
        ], [
            'a' => [
                'file:exists',
                ['file:size', 1] //1Kb
            ]
        ]);

        $tmpFile = $this->files->tempFilename();
        $this->files->write(
            $tmpFile,
            str_repeat('0', 1023)
        );

        clearstatcache();
        $this->assertFail('a', [
            'a' => new UploadedFile($tmpFile, filesize($tmpFile), 1)
        ], [
            'a' => [
                ['file:size', 1] //1Kb
            ]
        ]);
    }

    public function testExtension()
    {
        $this->assertFail('a', [], [
            'a' => [
                'file:exists',
                ['file:extension', 1] //1Kb
            ]
        ]);

        $this->assertValid([
            'a' => __FILE__
        ], [
            'a' => [
                'file:exists',
                ['file:extension', 'php']
            ]
        ]);

        $this->assertFail('a', [
            'a' => __FILE__
        ], [
            'a' => [
                'file:exists',
                ['file:extension', 'jpg']
            ]
        ]);
    }

    public function testExtensionUploaded()
    {
        $this->assertFail('a', [], [
            'a' => [
                'file:exists',
                ['file:extension', 1] //1Kb
            ]
        ]);

        $uploaded = new UploadedFile(__FILE__, filesize(__FILE__), 0, 'file.php');

        $this->assertValid([
            'a' => $uploaded
        ], [
            'a' => [
                'file:exists',
                ['file:extension', 'php']
            ]
        ]);

        $this->assertFail('a', [
            'a' => $uploaded
        ], [
            'a' => [
                'file:exists',
                ['file:extension', 'jpg']
            ]
        ]);
    }
}