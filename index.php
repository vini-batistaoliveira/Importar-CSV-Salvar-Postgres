<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">    
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<title>Importar CSV - PHP</title>


</head>
<body>

	<div class="container">

		<div class="row">

			<div class="col-md-12 jumbotron">
				<form id="formcsv" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="exampleFormControlFile1">Importar arquivo CSV</label> <br> <br>
						<input type="file" class="form-control-file" id="file" name="file">
						<input type="submit" name="sub" value="Importar">
					</div>
				</form>

				<?php

				//Abre string de conexão com a function PDO
				try {
					$con = new PDO("pgsql:host=localhost port=5432 dbname=postgres user=postgres password=1234");
				} catch (PDOException  $e) {
					print $e->getMessage();
				}

				if($con) {
					echo "Conectado ao banco de dados com sucesso!!! ";
					echo "<br>";
				} else{
					echo "Falha a se conectar com o banco de dados!!! ";
					return;
				}

				//Pega arquivo do input html fazendo post 
				if(isset($_POST["sub"])) {

					$check = $_FILES["file"]["tmp_name"];

					//Inicio leitura do arquivo
					$file = fopen($check , "r");

					while ($row = fgetcsv($file)) {

						foreach ($row as $key) {

							$csv = explode(";", $key);

							$stmt = $con->prepare('INSERT INTO contato (nome, sobrenome, endereco, email) VALUES (:nome,:sobrenome, :endereco, :email)');
							$stmt->bindValue(':nome', $csv[0], PDO::PARAM_STR);
							$stmt->bindValue(':sobrenome', $csv[1], PDO::PARAM_STR);
							$stmt->bindValue(':endereco', $csv[2], PDO::PARAM_STR);
							$stmt->bindValue(':email', $csv[3], PDO::PARAM_STR);

			   				 // Executa o insert do pdo
							$stmt->execute();

							  //apresentar os valores importados
							  echo "<table>
							  			<tr>
							  				<th> Nome </th>
							  				<th> Sobrenome </th>
							  				<th> Endereco </th>
							  				<th> Email </th>
							  			</tr>
								        <tr>
								           <td>" . $csv[0]."&nbsp </br></td>
						                   <td>" . $csv[1]."&nbsp </br></td>
						                   <td>" . $csv[2]."&nbsp </br></td>
						                   <td>" . $csv[3]."&nbsp </br></td>
						                </tr>
						            </table>"; 
						}          
					}

	    			//Fim da leitura CSV
					fclose($file);	

					if ($stmt->rowCount() > 0){	
						echo '<script language="javascript">';
						echo 'alert("Arquivo CSV foi importado com Sucesso!!!")';
						echo '</script>';
					} else {
						echo '<script language="javascript">';
						echo 'alert("Falha ao importar o arquivo CSV!!!")';
						echo '</script>';
					}



				}

				?>

			</div>
		</div>
	</div> 

</body>
</html>