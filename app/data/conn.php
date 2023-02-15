<?
	$total = 1/0;

	$con = mysqli_connect('db','rodfot','root');
	
	if (isset($con)) {
		
		echo 'Conectado'.'<br/>';

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
