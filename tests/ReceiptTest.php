<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
    public function testTotal()
    {
        $receipt = new Receipt();
        $this->assertEquals(
            15,
            $receipt->total([0, 2, 5, 8]),
            "When summing the total should equel 15"
        );
    }
}