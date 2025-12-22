<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

final class AddKey_Tinify_Command extends Tinify_Base_Command
{
    public function __construct()
    {
        parent::__construct(name: 'tini:add-key');
    }

    /**
     * Compress images menggunakan tinify API
     * 
     * ## Options
     * 
     * <key>
     * : Tinify API key
     * required: true
     * 
     * [--dry-run]
     * : Show only the files that would be modified
     * 
     * ## EXAMPLES
     *  
     *  # Add tinify API key
     *  $ wp tinify:add-key <key> --dry-run
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void {}
}
