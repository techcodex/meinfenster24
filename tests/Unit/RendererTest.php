<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\View\Renderer;
use PHPUnit\Framework\TestCase;

final class RendererTest extends TestCase
{
    public function testFailingTemplateDoesNotLeakOutputBuffer(): void
    {
        $dir = sys_get_temp_dir() . '/renderer_' . uniqid();
        mkdir($dir);
        file_put_contents($dir . '/boom.php', '<?php echo "partial"; throw new \RuntimeException("x");');
        $level = ob_get_level();

        try {
            (new Renderer($dir))->render('boom');
            self::fail('Exception expected');
        } catch (\RuntimeException) {
            self::assertSame($level, ob_get_level());
        } finally {
            unlink($dir . '/boom.php');
            rmdir($dir);
        }
    }
}
