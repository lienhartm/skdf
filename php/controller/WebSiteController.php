<?php

class WebSiteController
{

    public function not_access()
    {
        require_once 'view/not_access/index.php';
    }

    private function parseDateFr($date)
    {
        if (strpos($date, '/') !== false) {
            [$d, $m, $y] = explode('/', $date);
        } else {
            [$d, $m, $y] = explode('-', $date);
        }
        return mktime(0, 0, 0, $m, $d, $y);
    }

    public function index()
    {
        $actusJson = file_get_contents("documents/actualite/actus.json");
        $presseJson = file_get_contents("documents/presse/presse.json");
        $resultatsJson = file_get_contents("documents/resultat/resultat.json");

        $actus = json_decode($actusJson, true);
        $presses = json_decode($presseJson, true);
        $resultats = json_decode($resultatsJson, true);

        usort($actus, function ($a, $b) {
            return $this->parseDateFr($b['date']) <=> $this->parseDateFr($a['date']);
        });

        (!$actus) ? $actus = [] : $actus = array_slice($actus, 0, 3);
        (!$presses) ? $presses = [] : $presses;
        (!$resultats) ? $resultats = [] : $resultats;

        $telescript = [];
        $oneMonthAgo = (new DateTime())->modify('-1 month');

        foreach ($actus as $actu) {
            $date = isset($actu['date']) ? DateTime::createFromFormat('d/m/Y', $actu['date']) : false;
            if ($date && $date > $oneMonthAgo) {
                $telescript[] = $actu['titre'];
            }
        }
        foreach ($presses as $presse) {
            $date = isset($presse['date']) ? DateTime::createFromFormat('d/m/Y', $presse['date']) : false;
            if ($date && $date > $oneMonthAgo) {
                $telescript[] = $presse['titre'];
            }
        }
        foreach ($resultats as $resultat) {
            $date = isset($resultat['date']) ? DateTime::createFromFormat('d/m/Y', $resultat['date']) : false;
            if ($date && $date > $oneMonthAgo) {
                $telescript[] = $resultat['titre'];
            }
        }

        require_once 'view/index.php';
    }

    public function refuge()
    {
        require_once 'view/refuge/index.php';
    }

    public function reservation()
    {
        $pdo = new BD();
        $requete = "SELECT * FROM reservation";
        $stmt = $pdo->executeRequest($requete, []);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'view/reservation/index.php';
    }

    public function paypal()
    {
        require_once 'view/paypal/index.php';
    }

    public function club()
    {

        require_once 'view/club/index.php';
    }

    public function histoire()
    {
        require_once 'view/histoire/index.php';
    }

    public function actualite()
    {
        $actusJson = file_get_contents("documents/actualite/actus.json");
        $actus = json_decode($actusJson, true);

        usort($actus, function ($a, $b) {
            return $this->parseDateFr($b['date']) <=> $this->parseDateFr($a['date']);
        });
        require_once 'view/actualite/index.php';
    }

    public function presse()
    {
        $presseJson = file_get_contents("documents/presse/presse.json");
        $presses = json_decode($presseJson, true);

        usort($presses, function ($a, $b) {
            return $this->parseDateFr($b['date']) <=> $this->parseDateFr($a['date']);
        });
        require_once 'view/presse/index.php';
    }

    public function resultat()
    {
        $resultatJson = file_get_contents("documents/resultat/resultat.json");
        $resultats = json_decode($resultatJson, true);

        usort($resultats, function ($a, $b) {
            return $this->parseDateFr($b['date']) <=> $this->parseDateFr($a['date']);
        });
        require_once 'view/resultat/index.php';
    }

    public function acces()
    {
        require_once 'view/acces/index.php';
    }

    public function contact()
    {
        require_once 'view/contact/index.php';
    }
}