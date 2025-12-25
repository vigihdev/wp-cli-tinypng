<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliTinypng\Exceptions\BaseException;
use Vigihdev\WpCliTinypng\Service\TiniService;
use Vigihdev\WpCliTinypng\Validators\TiniValidator;
use Vigihdev\WpCliTools\Validators\{DirectoryValidator, FileValidator, ImageValidator};
use Vigihdev\WpCliTools\Builders\FileInfoBuilder;
use WP_CLI;
use WP_CLI\Utils;

final class Resize_Tinify_Command extends Tinify_Base_Command
{
    private TiniService $tini;

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
        $this->resize = (string)Utils\get_flag_value($assoc_args, 'resize', 'fit');
        $dryRun = (bool)Utils\get_flag_value($assoc_args, 'dry-run', false);
        $this->force = (bool)Utils\get_flag_value($assoc_args, 'force', false);

        $apiKey = '';
        try {
            $this->normalizeFilePath();
            $this->normalizeOutput();
            $this->calculatorWidthAndHeight();

            // Memastikan file yang di resize adalah file gambar 
            $this->validateFilepath();
            // FileValidator::validate($this->filepath)
            //     ->mustExist()
            //     ->mustBeMimeType()
            //     ->mustBeExtension(parent::ALLOW_EXTENSION);

            // Memastikan directory output ada dan dapat ditulis 
            $this->validateOutput();
            // $directory = Path::getDirectory($this->output);
            // DirectoryValidator::validate($directory)
            //     ->mustExist()
            //     ->mustBeWritable();

            // Memastikan resize method valid
            $this->validataTiniResize();
            // TiniValidator::validate()
            //     ->mustBeResizeMethod($resize)
            //     ->mustBeWidthMoreThanZero($this->width)
            //     ->mustBeHeightMoreThanZero($this->height);

            // Validate Client tinify API
            $this->tini = new TiniService(apiKey: $apiKey);
            $this->tini->connection();

            // Dry run, hanya menampilkan file yang akan di resize
            if ($dryRun) {
                $this->dryRun();
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
                "Total Compression : {$this->tini->getCompressionCount()}",
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
        $io->logInfo("Memulai proses resize image (ID: 30300)");

        // force overwrite file output jika di definisikan true, maka tidak akan ada confirmasi overwrite
        if ($this->force) {
            $io->logInfo("Force overwrite file output (ID: 30300)");
            return;
        }

        // Jika File Output exist, confirm apakah ingin di overwite
        if (is_file($this->output)) {
            $io->logInfo("File output sudah ada, akan di overwite (ID: 30300)");
            WP_CLI::confirm(
                $io->textWarning("Konfirmasi untuk melanjutkan")
            );
        }

        return;
        $resize = (bool)$this->tini->fromFile($this->filepath)->toFile($this->output);
        if ($resize) {
            $io->logSucess("Berhasil resize image (ID: 30300)");
            $io->renderBlock("Done Process Resize Image (ID: 30300)")->success();
            return;
        }
        $io->logError("Gagal resize image (ID: 30300)");
    }
}
