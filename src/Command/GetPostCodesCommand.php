<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\SoapClient\SoapClientBuilder;
use BeSimple\SoapClient\SoapClientOptionsBuilder;
use BeSimple\SoapCommon\SoapOptionsBuilder;

final class GetPostCodesCommand extends Command {

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('ukpc:get-post-codes')

            // the short description shown while running "php bin/console list"
            ->setDescription('Gets post codes for 2 or 3 towns in UK')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Provide 2 or 3 names of towns.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {





        $soapClientBuilder = new SoapClientBuilder();
        $soapClient = $soapClientBuilder->build(
            SoapClientOptionsBuilder::createWithDefaults(),
            SoapOptionsBuilder::createWithDefaults('http://www.webservicex.net/uklocation.asmx?WSDL')
        );
        $myRequest = new \stdClass();
        $myRequest->Town = 'London sdfsdfsdfds';
        $soapResponse = $soapClient->soapCall('GetUKLocationByTown', [$myRequest]);

        var_dump($soapResponse); // Contains Response, Attachments
    }
}