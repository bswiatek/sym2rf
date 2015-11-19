<?php
namespace Khepin\BookBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResizePictureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('picture:resize')
            ->setDescription('Resize a single picture')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to the picture you want to resize')
            ->addOption('size', null, InputOption::VALUE_REQUIRED, 'Size of the output picture (default 300 pixels)')
            ->addOption('out', 'o', InputOption::VALUE_REQUIRED, 'Folder which to output the picture (default sames as original picture)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Command line info
        $path = $input->getArgument('path');
        $size = $input->getOption('size');
        if (!$size) {$size = 300;}
        $out = $input->getOption('out');

        $this->getContainer()->get('shrinker')->shrinkImage($path, $out, $size);

        $output->writeln(sprintf('%s --> %s', $path, $out));
    }
}