<?php

try
{
    $bdd = new PDO("mysql:host=localhost; charset=UTF8",
				   "root",
				   "",
				   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				   ]
				  );
    $sql = "CREATE DATABASE GPBL";
    $bdd->exec($sql);
} catch(PDOException $e) {}

$bdd = new PDO("mysql:host=localhost; dbname=GPBL; charset=UTF8",
			   "root",
			   "",
			   [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			   ]
			  );

$sql = "CREATE TABLE IF NOT EXISTS `users` 
		(`ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		 `firstName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		 `lastName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		 `type` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
		 `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		 `birth` date NOT NULL,
		 `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
		 `country` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		 `IP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
		 `creatAt` timestamp NOT NULL DEFAULT current_timestamp(),
		 `updateAT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
		 `counter` int(10) UNSIGNED NOT NULL,
		 `question` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
		PRIMARY KEY (`ID`)
		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
$bdd->exec($sql);

if($_POST)
{
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$sex = $_POST["gender"];
	$email = $_POST["email"];
	$birthDate = $_POST["birthDate"];
	$phone = $_POST["phone"];
	$country = $_POST["country"];
	$question = $_POST["question"];
	$now = date("Y-m-d H:i:s");

	if(!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
        $ip = $_SERVER['REMOTE_ADDR'];
    }

	$query = $bdd->prepare("SELECT *
							FROM users
							WHERE email = :email");

	$query->execute(["email" => $email]);
	
	$user = $query->fetch();

	if ($user)
	{
		// USER ALREDY FOUND
		$id = $user["ID"];
		$update = strtotime($now);  
		$lastUpdate = strtotime($user["updateAT"]);  
		$diff = abs($update - $lastUpdate)/(60*60);

		if ($diff < 24)
		{
			$message = "Vous devez patienter au moins 24 heures après votre dernière inscription pour pouvoir vous réinscrire";
		}
		else
		{
			$counter = $user["counter"] + 1;

			$query = $bdd->prepare("UPDATE users
									SET
										firstName = :firstName,
										lastName = :lastName,
										type = :sex,
										birth = :birthDate,
										phone = :phone,
										country = :country,
										IP = :ip,
										counter = :counter,
										question = :question
									WHERE ID = :id");

			try
			{
				$query->execute([
								 "firstName" => $firstName,
								 "lastName" => $lastName,
								 "sex" => $sex,
								 "birthDate" => $birthDate,
								 "phone" => $phone,
								 "country" => $country,
								 "ip" => $ip,
								 "counter" => $counter,
								 "question" => $question,
								 "id" => $id,
								 ]);

				$message = "Votre inscription a été mise à jour";
			}
			catch(Exception $e)
			{
				$message = "Erreur pendant la mise à jour de votre inscription. Veuillez essayer à nouveau";
			}
		}
	}
	else
	{
		// USER NOT FOUND IN THE DB
		$counter = 1;

		$query = $bdd->prepare("INSERT INTO users (
								firstName,
								lastName,
								type,
								email,
								birth,
								phone,
								country,
								IP,
								creatAt,
								counter,
								question)
								VALUES (
								:firstName,
								:lastName,
								:sex,
								:email,
								:birthDate,
								:phone,
								:country,
								:ip,
								:creatAt,
								:counter,
								:question)
								");

		try
		{
			$query->execute([
							 "firstName" => $firstName,
							 "lastName" => $lastName,
							 "sex" => $sex,
							 "email" => $email,
							 "birthDate" => $birthDate,
							 "phone" => $phone,
							 "country" => $country,
							 "ip" => $ip,
							 "creatAt" => $now,
							 "counter" => 1,
							 "question" => $question,
							 ]);
			$message  = "Votre inscription a été prise en compte";
		}
		catch(Exception $e) 
		{
			$message = "Erreur pendant votre inscription à notre newsletter. Veuillez essayer à nouveau";
		}
	}
}

include "main.phtml";