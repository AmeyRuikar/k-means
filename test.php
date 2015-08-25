<?php
	class Car
	{
    		public $color;
    		public $type;
	}

	$myCar = new Car();
	$myCar->color = 'red';
	$myCar->type = 'sedan';

	$yourCar = new Car();
	$yourCar->color = 'blue';
	$yourCar->type = 'suv';

	$cars = array($myCar, $yourCar);
	
	$a="abc";
	$b=3;
	$a=$b;
	//echo $a;

	function dist($x1,$y1,$x2,$y2) {
		$temp = ($x1-$x2)*($x1-$x2) + ($y1-$y2)*($y1-$y2);
		return sqrt($temp);
	}

	echo dist(3,3,4,4);

?>
