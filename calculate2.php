<?php
	function dist($x1,$y1,$x2,$y2) {
		$temp = ($x1-$x2)*($x1-$x2) + ($y1-$y2)*($y1-$y2);
		return sqrt($temp);
	}

	if(!isset($_POST['points']) or !isset($_POST['numberOfPoints']) or !isset($_POST['numberOfClusters']))
	{
		$returnobject=array();
		$returnobject['errorcode']=1;
		echo json_encode($returnobject);
		//exit(); Uncomment later
	}
	$iterations=1;
	$idealpoints = array();
	$besterror=0;	
	$condition=0;
	$n = intval($_POST['numberOfPoints']);
	$k = intval($_POST['numberOfClusters']);
	$err="value of k = ".(string)($k);
	error_log($err, 3, "/var/tmp/k.log");

	class point{
		public $x,$y;
	}
		$stringJSON = get_magic_quotes_gpc() ? stripslashes($_POST['points']) : $_POST['points'];
		$points = json_decode($stringJSON,true);

		for($i=0;$i<$n;$i++)
		{
			$points[$i]['x']=floatval($points[$i]['x']);
			$points[$i]['y']=floatval($points[$i]['y']);
			$points[$i]['color']=intval($points[$i]['color']);
		}

		$means=array();
		$finalanswer=array();
		$flag=array();
		while(count($means) != $k)
		{
			$temp=rand(0,$n-1);
			if(!in_array($temp,$flag))
			{
				array_push($flag,$temp);
				$temp2=new point;
				$temp2->x = floatval($points[$temp]['x']);
				$temp2->y = floatval($points[$temp]['y']);
				array_push($means,$temp2);
			}
		}
		$stop=1;
		do
		{
			$stop=1;
			for($i=0;$i<$n;$i++)
			{
				$mindist = 1000000;
				$whichcolor = intval($points[$i]['color']);
				$originalcolor = intval($points[$i]['color']);
				for($j=0;$j<$k;$j++)
				{
					$tempdist = dist($means[$j]->x,$means[$j]->y,floatval($points[$i]['x']),floatval($points[$i]['y']));		
					if($tempdist < $mindist)
					{
						$mindist = $tempdist;
						$whichcolor = $j;
					}
				}

				$points[$i]['color']=$whichcolor;
				if($originalcolor != intval($points[$i]['color']))
				{
					$stop=0;
				}
			}


			for($j=0;$j<$k;$j++)
			{
				$x=0;
				$y=0;
				$N=0;
				for($i=0;$i<$n;$i++)
				{
					if(intval($points[$i]['color']) == $j)
					{
						
						$x = $x + floatval($points[$i]['x']);
						$y = $y + floatval($points[$i]['y']);
						$N = $N+1;
					}
				}
				if($N!=0)
				{
					$x=$x/$N;
					$y=$y/$N;
					$means[$j]->x=$x;
					$means[$j]->y=$y;
				}
			}
		}while($stop==0);
		
		$currenterror=0;
		for($i=0;$i<$n;$i++)
		{
			$j=$points[$i]['color'];
			$currenterror = $currenterror + dist($means[$j]->x,$means[$j]->y,floatval($points[$i]['x']),floatval($points[$i]['y']));
		}

	$points['n']=$n;
	$points['k']=$k;
	echo json_encode($points);
	
?>
