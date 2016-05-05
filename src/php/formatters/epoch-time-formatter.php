<?php
namespace DFF\Formatters;

class Epoch_Time_Formatter {

	function __construct( $epoch_time ) {
		$this->metric_seconds_multipliers = array(
			'minute' => MINUTE_IN_SECONDS,
			'hour'   => HOUR_IN_SECONDS,
			'day'    => DAY_IN_SECONDS,
			'month'  => MONTH_IN_SECONDS,
			'year'   => YEAR_IN_SECONDS,
		);

		$this->metric_noop_translations = array(
			'minute' => _n_noop(
				'%s minute',
				'%s minutes'
			),
			'hour' 	=> _n_noop(
				'%s hour',
				'%s hours'
			),
			'day' 	=> _n_noop(
				'%s day',
				'%s days'
			),
			'month'	=> _n_noop(
				'% month',
				'% months'
			),
			'year' 	=> _n_noop(
				'% year',
				'% years'
			),
		);

		$this->validate_epoch_time( $epoch_time );

		$this->epoch_time = $epoch_time;
	}

	public function to_human_readable() {

		$seconds_from_now = $this->calculate_seconds_from_now();

		$metrics = $this->distribute_seconds_to_metrics( $seconds_from_now );

		return $this->get_text_for_metrics( $metrics, $seconds_from_now );
	}

	private function calculate_seconds_from_now() {
		return $this->epoch_time - time();
	}

	private function immutable_arsort( $arr ) {
		arsort( $arr );
		return $arr;
	}

	private function map_metric_to_translation( $metric_key, $metric_qty ) {
		return sprintf( translate_nooped_plural( $this->metric_noop_translations[ $metric_key ], $metric_qty ), $metric_qty );
	}

	private function validate_epoch_time( $epoch_time ) {
		$given_time = 0;

		if ( isset( $epoch_time ) && intval( $epoch_time ) === $epoch_time ) {
			$given_time = $epoch_time;
		}

		if ( $given_time > 0 ) {
			return $given_time;
		} else {
			throw new \InvalidArgumentException('Incorrect epoch time passed');
		}
	}

	private function get_text_for_metrics( $metrics, $seconds_from_now ) {

		$time_text = '';

		if ( empty( $metrics ) ) {
			$time_text = __( 'less than a minute' );
		} else {
			$text_per_metric = array_map( array( $this, 'map_metric_to_translation' ), array_keys( $metrics ), $metrics );
			$time_text = implode( $text_per_metric, ', ' );
		}

		$final_text = '';

		if ( $seconds_from_now >= 0 ) {
			$final_text = sprintf(
				_x( 'in %s', 'future time distance' ),
				$time_text
			);
		} else {
			$final_text = sprintf(
				_x( '%s ago', 'past time distance' ),
				$time_text
			);
		}

		return $final_text;
	}

	private function distribute_seconds_to_metrics( $seconds ) {
		$seconds_currently_used = 0;
		$total_seconds_remaining = absint( $seconds );
		$desc_sorted_multipliers = $this->immutable_arsort( $this->metric_seconds_multipliers );
		$metrics = array();

		foreach ( $desc_sorted_multipliers as $multikey => $multivalue ) {
			$times_contained = intval( $total_seconds_remaining / $multivalue );
			if ( $times_contained > 0 && $total_seconds_remaining > 0 ) {
				$metrics[ $multikey ] = $times_contained;
				$seconds_currently_used = $times_contained * $multivalue;
				$total_seconds_remaining -= $seconds_currently_used;
			}
		}
		return $metrics;
	}
}
