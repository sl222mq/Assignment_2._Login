<?php

class DateTimeView {


	public function show() {

		$date = new DateTime();
		$timeString = $date->format("l").", the ".$date->format("jS")." of ".$date->format("F Y"). ", the time is ".$date->format("H:i:s"); 

		return '<p>' . $timeString . '</p>';
	}
}