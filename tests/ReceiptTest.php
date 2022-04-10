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
        unset($this->receipt);
    }

    public function testTotal()
    {
        //This is a good practice thing
        $input = [0, 2, 5, 8];
        $coupon = null;
        $output = $this->receipt->total($input, $coupon);

        $this->assertEquals(
            15,
            $output,
            "When summing the total should equel 15"
        );
    }

    public function testTotalAndCoupon()
    {
        //This is a good practice thing
        $input = [0, 2, 5, 8];
        $coupon = 0.20; //This is the DUMMY type of test double
        $output = $this->receipt->total($input, $coupon);

        $this->assertEquals(
            12,
            $output,
            "When summing the total should equel 12"
        );
    }

    //This is the example method of a MOCK type of test double
    public function testPostTaxTotal()
    {
        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'total'])
            ->getMock();
        $receipt->method('total')
            ->will($this->returnValue(10.00));
        $receipt->method('tax')
            ->will($this->returnValue(1.00));
        
        $result = $receipt->postTaxTotal([1,2,5,8], 0.20, null);

        $this->assertEquals(11.00, $result);

    }

    public function testTax()
    {
      $inputAmout = 10.00;
      $taxInput = 0.10;
      $output = $this->receipt->tax($inputAmout, $taxInput);

      $this->assertEquals(
          1.00,
          $output,
          "The tax calculation should equal 1.00"
      );
    }
}