<?php
namespace TDD\Test;

require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

use PHPUnit\Framework\TestCase;
use TDD\Receipt;

class ReceiptTest extends TestCase
{
    public function setUp()
    {
        $this->formatter = $this->getMockBuilder('TDD\Formatter')
            ->setMethods(['currencyAmt'])
            ->getMock();
        $this->formatter->expects($this->any())
            ->method('currencyAmt')
            ->with($this->anything())
            ->will($this->returnArgument(0));

        $this->receipt = new Receipt($this->formatter);
    }

    //This will unset any instance so PHPUnit doesn't carry anything from one test to the next
    public function tearDown()
    {
        unset($this->receipt);
    }

    /**
     * @dataProvider provideSubtotal 
     */
    public function testSubtotal($items, $expected)
    {
        //This is a good practice thing
        $coupon = null;
        $output = $this->receipt->subtotal($items, $coupon);

        $this->assertEquals(
            $expected,
            $output,
            "When summing the total should equel {$expected}"
        );
    }
    //If you want to test an especific item from the provideSubtotal use "--filter=testTotal#<number of item OR number - number, for more than one item to test>"
    public function provideSubtotal()
    {
        return [
            'ints totaling 16' => [[1,2,5,8], 16], //This one has a filter especification that can be used with "--filter='testTotal@ints totaling 16'"
            [[-1,2,5,8], 14],
            [[1,2,8], 11],
        ];
    }

    public function testSubtotalAndCoupon()
    {
        //This is a good practice thing
        $input = [0, 2, 5, 8];
        $coupon = 0.20; //This is the DUMMY type of test double
        $output = $this->receipt->subtotal($input, $coupon);

        $this->assertEquals(
            12,
            $output,
            "When summing the total should equel 12"
        );
    }

    public function testSubtotalException()
    {
        $input = [0, 2, 5, 8];
        $coupon = 1.20; 
        $this->expectException('BadMethodCallException');
        $this->receipt->subtotal($input, $coupon);

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
            ->setMethods(['tax', 'subtotal'])
            ->setConstructorArgs([$this->formatter])
            ->getMock();
        $receipt->expects($this->once())
            ->method('subtotal')
            ->with($items, $coupon)
            ->will($this->returnValue(10.00));
        $receipt->expects($this->once())
            ->method('tax')
            ->with(10.00)
            ->will($this->returnValue(1.00));
        
        $result = $receipt->postTaxTotal([1,2,5,8], null);

        $this->assertEquals(11.00, $result);

    }

    public function testTax()
    {
      $inputAmout = 10.00;
      $this->receipt->tax = 0.10;
      $output = $this->receipt->tax($inputAmout);

      $this->assertEquals(
          1.00,
          $output,
          "The tax calculation should equal 1.00"
      );
    }
}