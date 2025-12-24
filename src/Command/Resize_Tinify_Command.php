<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliTools\Validators\{DirectoryValidator, FileValidator, ImageValidator};
use Vigihdev\WpCliTools\Builders\FileInfoBuilder;
use WP_CLI;
use WP_CLI\Utils;

final class Resize_Tinify_Command extends Tinify_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'tini:resize');
    }

    /**
     * Resize images menggunakan tinify API
     * 
     * ## Options
     * 
     * <file>
     * : File yang akan di resize eg: (assets/img/tiny.png)
     * 
     * [--output=<filepath>]
     * : Filepath full directory dan extension untuk menyimpan file yang di resize      
     * required: true
     * 
     * [--width=<width>]
     * : Lebar gambar yang di resize
     * 
     * [--height=<height>]
     * : Tinggi gambar yang di resize
     * 
     * [--resize=<method>]
     * : Method resize gambar yang di resize, default adalah fit  
     * default: fit
     * options: 
     *  - scale
     *  - fit
     *  - cover
     *  - thumb
     * 
     * [--dry-run]
     * : Show only the files that would be modified
     * 
     * [--force]
     * : Force jika di definisikan, akan meng-overwrite file asli
     * 
     * ## EXAMPLES
     *  
     *  # Resize all images in assets/img to assets/img/tiny, resizing to 100x100 using cover method
     *  $ wp tini:resize assets/img --output=assets/img/tiny.png --width=100 --height=100 --resize=cover --dry-run
     * 
     *  # Resize all images in assets/img to assets/img/tiny, resizing to 100x100 using fit method
     *  $ wp tini:resize assets/img --output=assets/img/tiny.png --resize=fit
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $this->filepath = $args[0] ?? '';
        $this->output = (string)Utils\get_flag_value($assoc_args, 'output', '');
        $this->width = (int)Utils\get_flag_value($assoc_args, 'width', 0);
        $this->height = (int)Utils\get_flag_value($assoc_args, 'height', 0);
        $resize = (string)Utils\get_flag_value($assoc_args, 'resize', 'fit');
        $dryRun = (bool)Utils\get_flag_value($assoc_args, 'dry-run', false);
        $this->force = (bool)Utils\get_flag_value($assoc_args, 'force', false);

        try {
            $this->normalizeFilePath();
            $this->normalizeOutput();

            // Memastikan file yang di resize adalah file gambar
            FileValidator::validate($this->filepath)
                ->mustExist()
                ->mustBeMimeType()
                ->mustBeExtension(parent::ALLOW_EXTENSION);

            // Memastikan directory output ada dan dapat ditulis
            $directory = Path::getDirectory($this->output);
            DirectoryValidator::validate($directory)
                ->mustExist()
                ->mustBeWritable();

            // Validate Client tinify

            // Dry run
            if ($dryRun) {
                $this->dryRun();
                return;
            }

            // Skip cek exist output file jika di definisikan force
            if ($this->force) {
                $this->process();
                return;
            }

            // Jika Exist output file
            if (file_exists($this->output)) {
                $this->process();
                return;
            }

            $this->process();
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
    }

    private function dryRun(): void
    {
        $io = $this->io;
        $dryRun = $io->renderDryRunPreset("Resize image");
        $info = new FileInfoBuilder($this->filepath);
        $builder = $this->imageBuilder();
        $dryRun
            ->addInfo(
                "Resize image Source: {$this->filepath}",
                "Resize image Destination: {$this->output}",
            )
            ->addTableCompact([
                ['File', $info->getName(), $info->getName()],
                ['Extension', $info->getExtension(), $info->getExtension()],
                ['Size', $info->getSize(), $info->getSize()],
            ], ['Key', 'Source', 'Destination'])
        ;

        $dryRun->render();
    }

    private function process(): void
    {
        $io = $this->io;
        if ($this->force) {
        }

        if (is_file($this->output)) {
            $io->logInfo("File output sudah ada, akan di overwite (ID: 30300)");
            WP_CLI::confirm($io->textWarning("Konfirmasi untuk melanjutkan"));
        }

        $io->logInfo("Memulai proses resize image (ID: 30300)");
        $io->logError("Gagal resize image (ID: 30300)");
        $io->logSucess("Berhasil resize image (ID: 30300)");

        $io->renderBlock("Done Process Resize Image (ID: 30300)")->success();
    }
}
