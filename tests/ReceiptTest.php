<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
    public function setUp()
    {
        $this->receipt = new Receipt();
    }

    //This will unset any instance so PHPUnit doesn't carry anything from one test to the next
    public function tearDown()
    {
        unset($this->Receipt);
    }

    public function testTotal()
    {
        //This is a good practice thing
        $input = [0, 2, 5, 8];
        $output = $this->receipt->total($input);
        
        $this->assertEquals(
            15,
            $output,
            "When summing the total should equel 15"
        );
    }
}