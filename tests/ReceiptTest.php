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

    /**
     * @dataProvider provideTotal 
     */
    public function testTotal($items, $expected)
    {
        //This is a good practice thing
        $coupon = null;
        $output = $this->receipt->total($items, $coupon);

        $this->assertEquals(
            $expected,
            $output,
            "When summing the total should equel {$expected}"
        );
    }
    //If you want to test an especific item from the provideTotal use "--filter=testTotal#<number of item OR number - number, for more than one item to test>"
    public function provideTotal()
    {
        return [
            'ints totaling 16' => [[1,2,5,8], 16], //This one has a filter especification that can be used with "--filter='testTotal@ints totaling 16'"
            [[-1,2,5,8], 14],
            [[1,2,8], 11],
        ];
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

    public function testTotalException()
    {
        $input = [0, 2, 5, 8];
        $coupon = 1.20; 
        $this->expectException('BadMethodCallException');
        $this->receipt->total($input, $coupon);

    }

    //This is the example method of a STUB type of test double
    // public function testPostTaxTotal()
    // {
    //     $receipt = $this->getMockBuilder('TDD\Receipt')
    //         ->setMethods(['tax', 'total'])
    //         ->getMock();
    //     $receipt->method('total')
    //         ->will($this->returnValue(10.00));
    //     $receipt->method('tax')
    //         ->will($this->returnValue(1.00));
        
    //     $result = $receipt->postTaxTotal([1,2,5,8], 0.20, null);

    //     $this->assertEquals(11.00, $result);

    // }

    //This is the example method of a MOCK type of test double
    public function testPostTaxTotal()
    {
        $items = [1,2,5,8];
        $tax = 0.20;
        $coupon = null;
        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'total'])
            ->getMock();
        $receipt->expects($this->once())
            ->method('total')
            ->with($items, $coupon)
            ->will($this->returnValue(10.00));
        $receipt->expects($this->once())
            ->method('tax')
            ->with(10.00, $tax)
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

    /**
     * @dataProvider provideCurrencyAmt
     */

    public function testCurrencyAmt($input, $expected, $msg)
    {
        $this->assertSame(
            $expected,
            $this->receipt->currencyAmt($input),
            $msg
        );
    }

    public function provideCurrencyAmt(){
        return [
            [1, 1.00, '1 should be transformed into 1.00'],
            [1.1, 1.10, '1.1 should be transformed into 1.10'],
            [1.11, 1.11, '1.11 should stay as 1.11'],
            [1.111, 1.11, '1.111 should be transformed into 1.11'],
        ];
    }
}