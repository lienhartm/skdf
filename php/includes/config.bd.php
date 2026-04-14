<?php

class BD
{

	private $bdd;

	public function __construct()
	{
		try {
			$this->bdd = new PDO('mysql:host=db;dbname=scg;charset=utf8', 'root', 'root');
			$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Connexion échouée : ' . $e->getMessage();
		}
	}

	function getBD()
	{
		return $this->bdd;
	}

	public function executeRequest($sql, $params = NULL)
	{
		try {
			if ($params == NULL) {
				$resultat = $this->bdd->query($sql);
			} else {
				$resultat = $this->bdd->prepare($sql);
				$resultat->execute($params);
			}
			return $resultat;
		} catch (PDOException $e) {
			throw new Exception("Erreur : " . $e->getMessage());
		}
	}

	public function lastInsertId()
	{
		return $this->bdd->lastInsertId();
	}
}

?>