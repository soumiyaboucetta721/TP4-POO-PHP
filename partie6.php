
<?php

// =====================================
// 6.1 - Héritage avec Animal et Chien
// =====================================

class Animal {
    public function manger(): void {
        echo "Je mange." . PHP_EOL;
    }
}

class Chien extends Animal {
    public function aboyer(): void {
        echo "Wouf !" . PHP_EOL;
    }
}

class Chat extends Animal {
    public function miauler(): void {
        echo "Miaou !" . PHP_EOL;
    }
}

$c = new Chien();
$c->manger();
$c->aboyer();

$chat = new Chat();
$chat->manger();
$chat->miauler();


// =====================================
// 6.2 - parent:: et redéfinition
// =====================================

class PersonneDemo {
    public function __construct(protected string $nom) {}

    public function presentation(): string {
        return "Je suis " . $this->nom;
    }
}

class EtudiantDemo extends PersonneDemo {
    public function __construct(
        string $nom,
        private string $filiere
    ) {
        parent::__construct($nom);
    }

    public function presentation(): string {
        return parent::presentation()
            . ", étudiant en " . $this->filiere;
    }
}

$demo = new EtudiantDemo("Ahmed", "Informatique");
echo $demo->presentation() . PHP_EOL;


// =====================================
// 6.3 - final et readonly
// =====================================

class Base {
    final public function afficher(): void {
        echo "Méthode finale" . PHP_EOL;
    }
}

final class Outil {
    public const VERSION = "1.0";
}

class Produit {
    public function __construct(
        public string $reference
    ) {}
}

$produit = new Produit("REF-001");
echo $produit->reference . PHP_EOL;


// =====================================
// 6.4 - Etudiant hérite de Personne
// =====================================

class Personne {

    public string $nom;
    public string $prenom;

    private string $dateNaissance;
    private string $villeNaissance;
    private string $cin;

    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->villeNaissance = $villeNaissance;
        $this->cin = $cin;
    }

    private function getAge(): int {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();

        return $naissance->diff($aujourdhui)->y;
    }

    public function __toString(): string {
        return $this->prenom . " " . $this->nom
            . " (" . $this->getAge() . " ans)";
    }
}

class Etudiant extends Personne {

    private string $cne;

    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin,
        string $cne
    ) {
        parent::__construct(
            $nom,
            $prenom,
            $dateNaissance,
            $villeNaissance,
            $cin
        );

        $this->cne = $cne;
    }

    public function getCne(): string {
        return $this->cne;
    }

    public function __toString(): string {
        return parent::__toString()
            . " | CNE : " . $this->getCne();
    }
}


// Création de 5 étudiants

$etudiants = [];

$etudiants[] = new Etudiant(
    "Boucetta", "Somya", "2006-05-14",
    "Zagora", "EE123456", "R130000001"
);

$etudiants[] = new Etudiant(
    "Hachimi", "Sara", "2000-05-14",
    "Marrakech", "EE123457", "R130000002"
);

$etudiants[] = new Etudiant(
    "Amrani", "Ahmed", "2004-03-20",
    "Rabat", "EE123458", "R130000003"
);

$etudiants[] = new Etudiant(
    "Fassi", "Salma", "2003-08-10",
    "Fès", "EE123459", "R130000004"
);

$etudiants[] = new Etudiant(
    "Alaoui", "Youssef", "2002-12-01",
    "Casablanca", "EE123460", "R130000005"
);


// Affichage des étudiants

echo PHP_EOL . "--- Liste des étudiants ---" . PHP_EOL;

foreach ($etudiants as $e) {
    echo $e . PHP_EOL;
}
