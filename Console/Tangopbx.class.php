<?php

namespace FreePBX\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Tangopbx extends Command
{

    protected function configure()
    {
        $this->setName('tangopbx')
            ->setDescription('Mirror Functions')
            ->setDefinition(array(
                new InputOption('info', '', InputOption::VALUE_NONE, 'Get Info'),
            ))
            ->setHelp('Tasks related to custom mirror');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $FreePBX = \FreePBX::Create();
        $cipb = $FreePBX->Tangopbx;

        if ($input->getOption('info')) {
            $output->writeln(json_encode($cipb->getSupportData(), JSON_PRETTY_PRINT));
            return;
        }
    }
}
