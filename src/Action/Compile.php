<?php

declare(strict_types=1);

/**
 * The main Compiler action.
 * 
 * @method void __invoke()
 */

namespace PinkCrab\Simple_MD_Compiler\Action;

use Symfony\Component\Console\Output\OutputInterface;

class Compile
{

    public function __invoke(OutputInterface $output): void
    {
        $output->writeln('Hello World');
    }
}
