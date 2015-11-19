<?php
namespace Khepin\BookBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class UpdateProfilePicsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('picture:profile:update')
            ->setDescription('Resizes all user\'s pictures to a new size')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get information interactively
        $dialog = $this->getHelperSet()->get('dialog');
        $size = $dialog->ask($output, 'Size of the final pictures (300): ', '300');
        $out = $dialog->ask($output, 'Output folder: ');

        // Get list of all users
        $users = $this->getContainer()->get('fos_user.user_manager')->findUsers();

        // Show the user progress of the ongoing command
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($output, count($users));

        foreach($users as $user) {
            $path = $user->getPicture();
            $this->getContainer()->get('shrinker')->shrinkImage($path, $out, $size);
            // Advance progress
            $progress->advance();
        }

        // Show that the whole process was successful
        $output->writeln('');
        $output->writeln('<info>Success!</info>');
    }
}