<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Vigihdev\WpCliModels\Validators\FileValidator;
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

        try {
            FileValidator::validate($this->filepath)
                ->mustExist();
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($this->io, $e);
        }
    }

    private function dryRun(): void
    {
        $io = $this->io;
        $io->success(sprintf('Dry run: %s -> %s', $this->filepath, $this->output));
    }

    private function process(): void
    {
        $io = $this->io;
        $io->success(sprintf('Process: %s -> %s', $this->filepath, $this->output));
    }
}
