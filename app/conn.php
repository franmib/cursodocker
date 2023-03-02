<?php
	

	$con = mysqli_connect('fbomysql-service:3306','root','super');
	
	if (isset($con)) {
		
		echo 'Conectado2'.'<br/>';

		mysqli_select_db($con,'prueba');

		$query = 'select * from prueba';
		
		$result = mysqli_query($con,$query);
		
		if($result) {
			while($row = mysqli_fetch_array($result)){
				$name = $row["col"];
				echo $name." ";
			}
		}

		return;
	}

	echo 'Sin conexión';

?>
