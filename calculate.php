<?php
	//Utility function to calculate Eucledian distance
	function dist($x1,$y1,$x2,$y2) {
		$temp = ($x1-$x2)*($x1-$x2) + ($y1-$y2)*($y1-$y2);
		return sqrt($temp);
	}
	
	if(isset($_POST['points']) and $_POST['numberofpoints']){
		$numberofpoints = intval($_POST['numberofpoints']);
		$points = json_decode($_POST['points'],true);	
		

		//Actual processing.

		//In first version, only 3 clusters.
		$k=3;
		
		//Need validation for when number of points less than 3 ..... 

		//A class to define the means
		class point {
			public $x;
			public $y;
		}

		//In first version, the initial 3 means are the first 3 points
		$means = array();

		for($i=0;$i<$k;$i++) {
			$temp = new point;
			$temp -> x = $points[$i]['x'];
			$temp -> y = $points[$i]['y'];
			array_push($means,$temp);
			//print_r($means);
		}

		//This is the main loop. Keep doing stuff inside till no point changes its position.
		//To indicate this we use a flag, the flag is initially set to 0, if any point changes its color, value of flag becomes 1 
		//For some reason, stuff is running infinitely. Lets also pause when a certain number of iterations are over.
		$flag=0;
		$iterations = $numberofpoints * $numberofpoints;
		do {
			
			$flag=0;
			$iterations = $iterations - 1;
			//For each point, calculate its distance from the k means and assign a color to each point
			for($i=0;$i<$numberofpoints;$i++) {
				$mindistance = 1000000;
				$whichcolor = -1;
				
				$tempx = $points[$i]['x'];
				$tempy = $points[$i]['y'];
				$originalcolor = $points[$i]['color'];				

				//This loop iterates through all k means
				for($j=0;$j<$k;$j++) {
					$tempdistance = dist($means[$j]->x , $means[$j] -> y , $tempx , $tempy);
					if ($tempdistance < $mindistance) {
						$mindistance = $tempdistance;
						$whichcolor = $j;
					}
				}

				//Now assign whichcolor to the point i
				$points[$i]['color'] = $whichcolor;
				
				if( $originalcolor != $points[$i]['color']) {
					$flag=1;
				}
			}	

		
			//Calculate new value of means based on the points allocated to each mean
			//This loop goes through each color and calculates a mean for it
			for($i=0;$i<$k;$i++) {
				$n=0;
				$tempx=0;
				$tempy=0;			

				//Loop through all the points and if it belongs to this color then use it to determine the new mean
				for($j=0;$j<$numberofpoints;$j++) {
					if($points[$j]['color'] == $i) {
						$tempx=$tempx + $points[$j]['x'];
						$tempy=$tempy + $points[$j]['y'];
						$n=$n+1;
					}
				}
						
				$tempx = $tempx / $n;
				$tempy = $tempy / $n;

				//Assign new means 
				$means[$i]->x = $tempx;
				$means[$i]->y = $tempy;			
			}

		} while($flag == 1);
		echo json_encode($points);	
	}
	else{
		echo "Did not receive valid data!";
	}
?>
