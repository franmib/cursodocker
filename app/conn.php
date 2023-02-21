<?php
	

	$con = mysqli_connect('db','rofot','root');
	
	if (isset($con)) {
		
		echo 'Conectado2'.'<br/>';

		mysqli_select_db($con,'prueba');

		$query = 'select * from prueba';
		
		$result = mysqli_query($con,$query);
		
		if($result) {
			while($row = mysqli_fetch_array($result)){
				$name = $row["pruebacol"];
				echo $name." ";
			}
		}

		return;
	}

	echo 'Sin conexión';

?>
