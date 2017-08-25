<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\SoapClient\SoapClientBuilder;
use BeSimple\SoapClient\SoapClientOptionsBuilder;
use BeSimple\SoapClient\SoapClient;
use BeSimple\SoapCommon\SoapOptionsBuilder;
use Symfony\Component\DomCrawler\Crawler;
use SoapFault;


final class GetPostCodesCommand extends Command {

    const METHOD_NAME = 'GetUKLocationByTown';
    const WSDL_URL = 'http://www.webservicex.net/uklocation.asmx?WSDL';
    const RESPONSE_ITEM_XPATH = '//table';
    const RESPONSE_DATA_XPATH = '//table/*';

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
        try {
            $soapClientBuilder = new SoapClientBuilder();
            $soapClient = $soapClientBuilder->build(
                SoapClientOptionsBuilder::createWithDefaults(),
                SoapOptionsBuilder::createWithDefaults(self::WSDL_URL)
            );
        }
        catch (SoapFault $sfex) {
            echo 'WSDL unavailable or broken.'."\n";
            error_log($sfex->getMessage());
            exit(1);
        }
        try {
            $townData = $this->requestPostCodeByTown('London', $soapClient);
            $this->echoPostCodes($townData);
            $townData = $this->requestPostCodeByTown('Manchester', $soapClient);
            $this->echoPostCodes($townData);

        }
        catch (SoapFault $sfex) {
            echo 'SOAP request failed.'."\n";
            error_log($sfex->getMessage());
            exit(2);
        }




    }

    private function echoPostCodes($data) {
        foreach ($data as $item) {
            echo $item['postcode']. ' - ' . $item['town'] . ' - ' . $item['county']. "\n";
        }
    }

    private function requestPostCodeByTown($town, SoapClient $soapClient) {
            $myRequest = new \stdClass();
            $myRequest->Town = $town;
            $soapResponse = $soapClient->soapCall(self::METHOD_NAME, [$myRequest]);

            $document = $soapResponse->getContentDocument();

            $crawler = new Crawler($document->textContent);

            $crawler = $crawler->filterXPath(self::RESPONSE_ITEM_XPATH);

            $data = $this->parseDom($crawler);

            return $data;
    }

    private static function parseDom(Crawler $dom) {
        // adjusted from https://stackoverflow.com/questions/38065659/how-to-parse-html-table-to-array-with-symfony-dom-crawler
        $ret = [];
        foreach ($dom as $content) {
            $item = array();
            $crawler = new Crawler($content);
            foreach ($crawler->filterXPath(self::RESPONSE_DATA_XPATH) as $node) {
                $item[$node->nodeName] = $node->nodeValue;
            }
            $ret[] = $item;
        }
        return $ret;
    }



}