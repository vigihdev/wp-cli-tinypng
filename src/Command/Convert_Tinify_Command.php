<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

final class Convert_Tinify_Command extends Tinify_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'tini:convert');
    }

    /**
     * Convert images menggunakan tinify API
     * 
     * ## Options
     * 
     * <file>
     * : File yang akan di convert eg: (assets/img/tiny.png)
     * 
     * [--output=<filepath>]
     * : Filepath full directory dan extension untuk menyimpan file yang di convert
     * required: true
     * 
     * [--width=<width>]
     * : Lebar gambar yang di convert
     * 
     * [--height=<height>]
     * : Tinggi gambar yang di convert
     * 
     * [--resize=<method>]
     * : Method resize gambar yang di convert, default adalah cover 
     * 
     * [--dry-run]
     * : Show only the files that would be modified
     * 
     * ## EXAMPLES
     *  
     *  # Convert all images in assets/img to assets/img/tiny, resizing to 100x100 using cover method
     *  $ wp tini:convert assets/img --output=assets/img/tiny.png --width=100 --height=100 --resize=cover --dry-run
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void {}
}
