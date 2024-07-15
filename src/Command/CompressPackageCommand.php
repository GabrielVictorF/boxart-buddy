<?php

namespace App\Command;

readonly class CompressPackageCommand implements CommandInterface
{
    public const NAME = 'compress-package';

    public function __construct(
        public string $packageName,
        public array $nukeOptions
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
