
<?php

class Personne {

    private string $nom = "";
    private string $prenom = "";
    private string $dateNaissance = "";

    
public function __set(string $name, mixed $value): void {

    if (!property_exists($this, $name)) {
        echo "Propriété $name inexistante" . PHP_EOL;
        return;
    }

    if ($name === "nom") {
        if (strlen($value) < 2) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 2 caractères.");
        }
    }

    $this->$name = $value;
}

    // __get : lire une propriété
    public function __get(string $name): mixed {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    // __isset : vérifier si une propriété existe et n'est pas null
    public function __isset(string $name): bool {
        return property_exists($this, $name) && isset($this->$name);
    }

    // __unset : vider une propriété
    public function __unset(string $name): void {
        if (property_exists($this, $name)) {
            $this->$name = "";
        }
    }
}

// Programme de test

$p = new Personne();

$p->nom = "Hachimi";

echo $p->nom . PHP_EOL;

var_dump(isset($p->nom));

unset($p->nom);

var_dump(isset($p->nom));

$p->telephone = "0600000000";

try {
    $p->nom = "Ali";
    echo $p->nom . PHP_EOL;

    $p->nom = "";
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

