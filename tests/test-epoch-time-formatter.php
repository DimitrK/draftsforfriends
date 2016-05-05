<?php
/**
* Class Epoch_Time_Formatter_Test
*
* @package 
*/

/**
* Sample test case.
*/
//class SampleTest extends WP_UnitTestCase {
//
///**
// * A single example test.
// */
//function test_sample() {
//	// Replace this with some actual testing code.
//	$this->assertTrue( true );
//}
//}

namespace DFF\Formatters;


use DFF\Formatters\Epoch_Time_Formatter as Epoch_Formatter;

/**
* Override time() in current namespace for testing since Epoch_Time_Formatter
* is using it in order to calculate the difference from the given time stamp.
*
* @return int
*/
function time() {
	return Epoch_Time_Formatter_Test::$epoch_now ?: \time();
}

class Epoch_Time_Formatter_Test extends \WP_UnitTestCase {
	/**
	 * @var int $epoch_now Timestamp that will be returned by time()
	 */
	public static $epoch_now;

	/**
	 * @var Epoch_Time_Formatter $epoch_formatter Test subject
	 */
	private $epoch_formatter;

	/**
	 * Create test subject before test
	 */
	public function setUp() {
		parent::setUp();
		$formatter_time = strtotime('Sunday, 17-April-2016 15:00:00 UTC');
		$this->epoch_formatter = new Epoch_Formatter($formatter_time);
	}
	/**
	 * Reset custom time after test
	 */
	public function tearDown()	{
		self::$epoch_now = null;
	}
	
	/*
	 * Eedge case tests.
	 */
	
	public function test_date_is_same_as_now() {

		self::$epoch_now = strtotime('Sunday, 17-April-2016 15:00:00 UTC');
				
		$this->assertEquals('in less than a minute', $this->epoch_formatter->to_human_readable());
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_text_string_as_date_given() {

		$this->epoch_formatter = new Epoch_Formatter('An invalid string date');
				
		$this->epoch_formatter->to_human_readable();
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_numeric_string_as_date_given() {

		$this->epoch_formatter = new Epoch_Formatter('123124512123');
				
		$this->epoch_formatter->to_human_readable();
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_empty_string_passed() {
		
		$this->epoch_formatter = new Epoch_Formatter('');
				
		$this->epoch_formatter->to_human_readable();
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_null_passed() {

		$this->epoch_formatter = new Epoch_Formatter(NULL);
				
		$this->epoch_formatter->to_human_readable();
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_zero_passed() {
		
		$this->epoch_formatter = new Epoch_Formatter(0);
				
		$this->epoch_formatter->to_human_readable();
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_invalid_negative_number_passed() {
		
		$this->epoch_formatter = new Epoch_Formatter(-10);
				
		$this->epoch_formatter->to_human_readable();
	}
	
	/*
	 * Test cases where `now` date is considered to be ahead of the given time on each scenario. 
	 * In future.
	 */
	public function test_singular_one_day_ahead_english() {

		self::$epoch_now = strtotime('Saturday, 16-April-2016 15:00:00 UTC');
				
		$this->assertEquals('in 1 day', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_plural_two_days_ahead_english() {

		self::$epoch_now = strtotime('Friday, 15-April-2016 15:00:00 UTC');
				
		$this->assertEquals('in 2 days', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_singular_one_hour_ahead_english() {

		self::$epoch_now = strtotime('Saturday, 16-April-2016 14:00:00 UTC');
				
		$this->assertEquals('in 1 day, 1 hour', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_one_hour_plural_two_hours_ahead_english() {

		self::$epoch_now = strtotime('Saturday, 16-April-2016 13:00:00 UTC');
				
		$this->assertEquals('in 1 day, 2 hours', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_one_hour_one_hour_singular_one_minute_ahead_english() {

		self::$epoch_now = strtotime('Saturday, 16-April-2016 13:59:00 UTC');
				
		$this->assertEquals('in 1 day, 1 hour, 1 minute', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_one_hour_one_hour_plural_ten_minutes_ahead_english() {

		self::$epoch_now = strtotime('Saturday, 16-April-2016 13:50:00 UTC');
				
		$this->assertEquals('in 1 day, 1 hour, 10 minutes', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_less_than_one_minute_ahead_english() {

		self::$epoch_now = strtotime('Sunday, 17-April-2016 14:59:30 UTC');
				
		$this->assertEquals('in less than a minute', $this->epoch_formatter->to_human_readable());
	}
	
	/*
	 * Test cases where `now` date is considered to be prior to the given time on each scenario.
	 * In past.
	 */
	public function test_singular_one_day_ago_english() {

		self::$epoch_now = strtotime('Monday, 18-April-2016 15:00:00 UTC');
				
		$this->assertEquals('1 day ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_plural_two_days_ago_english() {

		self::$epoch_now = strtotime('Tuesday, 19-April-2016 15:00:00 UTC');
				
		$this->assertEquals('2 days ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_singular_one_hour_ago_english() {

		self::$epoch_now = strtotime('Monday, 18-April-2016 16:00:00 UTC');
				
		$this->assertEquals('1 day, 1 hour ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_plural_two_hours_ago_english() {

		self::$epoch_now = strtotime('Monday, 18-April-2016 17:00:00 UTC');
				
		$this->assertEquals('1 day, 2 hours ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_one_hour_singular_one_minute_ago_english() {

		self::$epoch_now = strtotime('Monday, 18-April-2016 16:01:00 UTC');
				
		$this->assertEquals('1 day, 1 hour, 1 minute ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_one_day_one_hour_plural_ten_minutes_ago_english() {

		self::$epoch_now = strtotime('Monday, 18-April-2016 16:10:00 UTC');
				
		$this->assertEquals('1 day, 1 hour, 10 minutes ago', $this->epoch_formatter->to_human_readable());
	}
	
	public function test_less_than_one_minute_ago_english() {

		self::$epoch_now = strtotime('Sunday, 17-April-2016 15:00:30 UTC');
				
		$this->assertEquals('less than a minute ago', $this->epoch_formatter->to_human_readable());
	}
}