<?php
namespace Tests\Command;

use PHPUnit\Framework\TestCase;
use App\Command\GetPostCodesCommand;

class GetPostCodesCommandTest extends TestCase
{
    public function testParseDom()
    {
        $xml = <<<DATA
              <Table>
                <Town>Little London</Town>
                <County>East Sussex</County>
                <PostCode>TN21</PostCode>
              </Table>
              <Table>
                <Town>Little London</Town>
                <County>Hampshire</County>
                <PostCode>RG26</PostCode>
              </Table>
              <Table>
                <Town>Little London</Town>
                <County>Hampshire</County>
                <PostCode>SP11</PostCode>
              </Table>
              <Table>
                <Town>Little London</Town>
                <County>Isle of Man</County>
                <PostCode>IM6</PostCode>
              </Table>
              <Table>
                <Town>Little London</Town>
                <County>Lincolnshire</County>
                <PostCode>LN9</PostCode>
              </Table>
              <Table>
                <Town>Little London</Town>
                <County>Lincolnshire</County>
                <PostCode>PE11</PostCode>
              </Table>
DATA;

        $resultArray = GetPostCodesCommand::crawlData($xml);

        $expectedResult = array (
            0 => array (
                'town' => 'Little London',
                'county' => 'East Sussex',
                'postcode' => 'TN21',
            ),
            1 => array (
                'town' => 'Little London',
                'county' => 'Hampshire',
                'postcode' => 'RG26',
            ),
            2 => array (
                'town' => 'Little London',
                'county' => 'Hampshire',
                'postcode' => 'SP11',
            ),
            3 => array (
                'town' => 'Little London',
                'county' => 'Isle of Man',
                'postcode' => 'IM6',
            ),
            4 => array (
                'town' => 'Little London',
                'county' => 'Lincolnshire',
                'postcode' => 'LN9',
            ),
            5 => array (
                'town' => 'Little London',
                'county' => 'Lincolnshire',
                'postcode' => 'PE11',
            ),
        );


        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expectedResult, $resultArray);
    }
}
