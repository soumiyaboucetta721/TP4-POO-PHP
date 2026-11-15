# TP 4 – Programmation Orientée Objet en PHP
## Réponses aux questions

**Nom et prénom :** Somya Boucetta  
**Groupe :** 2

---

# Partie 0 – La première classe

### 0.1 – Créer un second objet `$v2`

```php
$v2 = new Voiture();
$v2->setMarque("Renault");
$v2->afficher();
```

### 0.2 – Ajouter une propriété couleur

```php
public $couleur;

public function setCouleur($c) {
    $this->couleur = $c;
}

public function afficher() {
    echo "Marque : " . $this->marque . PHP_EOL;
    echo "Couleur : " . $this->couleur . PHP_EOL;
}
```

### 0.3 – Les objets partagent-ils la même valeur de marque ?

Non, les objets `$v1` et `$v2` ne partagent pas la même valeur de `$marque`. Chaque objet possède ses propres propriétés. Modifier la marque de l'un ne modifie pas celle de l'autre.

---

# Partie 1 – Visibilité et encapsulation

### 1.1 – Que remarquez-vous ?

- `$p->cin` : provoque une erreur, car l'attribut `cin` est privé.
- `$p->dateNaissance` : provoque une erreur, car l'attribut `dateNaissance` est privé.
- `$p->getAge()` : provoque une erreur, car la méthode `getAge()` est privée.

Ces éléments ne sont pas accessibles directement depuis l'extérieur de la classe.

### 1.2 – Pourquoi serait-il dangereux de laisser `cin` en public ?

Il serait dangereux de laisser `cin` en public, car n'importe quel code extérieur pourrait consulter ou modifier directement cette information personnelle. Il est préférable de protéger cet attribut avec la visibilité `private`.

### 1.3 – `getAge()` est privée : est-elle donc inutile ?

Non, la méthode `getAge()` n'est pas inutile. Elle peut être appelée par une autre méthode de la même classe, par exemple `__toString()`.

### 1.4 – Changer `cin` en protected

Non, le comportement depuis l'extérieur ne change pas. Un attribut `protected` n'est pas accessible directement depuis l'extérieur de la classe. Il est accessible uniquement dans la classe elle-même et ses classes filles.

---

# Partie 2 – Constructeurs et destructeur

## 2.1 – Constructeur avec valeurs par défaut

### 2.1.a – Que se passe-t-il avec `new Voiture("Noire")` et `new Voiture(120)` ?

- `new Voiture("Noire")` : la couleur devient « Noire » et la vitesse prend la valeur par défaut `0`.
- `new Voiture(120)` : sans typage strict, PHP peut convertir automatiquement le nombre `120` en chaîne de caractères `"120"`. Avec `strict_types`, une erreur `TypeError` se produit, car le premier paramètre doit être une chaîne.

### 2.1.b – Quel est l'avantage de la promotion de propriétés ?

La promotion de propriétés permet de déclarer et d'initialiser directement les attributs dans les paramètres du constructeur. Elle réduit le code et évite de répéter les déclarations et les affectations avec `$this`.

Exemple :

```php
public function __construct(
    public string $couleur = "Blanche",
    public int $vitesse = 0
) {}
```

## 2.2 – Le piège des deux constructeurs

### 2.2.a – Que remarquez-vous à l'exécution ? Pourquoi ?

Une erreur fatale se produit : `Cannot redeclare Personne::__construct()`.

En PHP, une classe ne peut pas avoir deux méthodes portant le même nom. Il est donc impossible de définir deux constructeurs dans une même classe.

### 2.2.b – Proposer une solution

On peut utiliser des paramètres avec des valeurs par défaut :

```php
public function __construct(string $nom = "", string $prenom = "") {
    $this->nom = $nom;
    $this->prenom = $prenom;
}
```

Ainsi, on peut créer un objet vide avec `new Personne()` ou un objet avec des informations avec `new Personne("Hachimi", "Sara")`.

### 2.2.c – Qu'est-ce qu'une méthode statique de fabrique ?

Une méthode statique de fabrique permet de créer et de retourner un objet en utilisant directement le nom de la classe, sans appeler directement le constructeur.

Exemple :

```php
public static function depuisTableau(array $data): Personne {
    return new Personne($data['nom'], $data['prenom']);
}
```

Appel :

```php
$p = Personne::depuisTableau([
    'nom' => 'Hachimi',
    'prenom' => 'Sara'
]);
```

## 2.3 – Le destructeur

### 2.3.a – Dans quel ordre les trois messages s'affichent-ils ?

L'ordre est :

1. `Voiture Blanche créée.`
2. `Voiture Blanche détruite.`
3. `Fin du script.`

La méthode `__destruct()` est appelée automatiquement lors de l'exécution de `unset()`.

### 2.3.b – Supprimer la ligne `unset()`

Sans `unset()`, l'objet existe jusqu'à la fin du script. L'ordre devient :

1. `Voiture Blanche créée.`
2. `Fin du script.`
3. `Voiture Blanche détruite.`

### 2.3.c – Créer deux objets `$a` et `$b`

Les objets sont généralement détruits dans l'ordre inverse de leur création : le dernier objet créé est le premier à être détruit, donc `$b` puis `$a`.

### 2.3.d – Ajouter un destructeur dans Personne

```php
public function __destruct() {
    echo "Détruire " . __CLASS__ . PHP_EOL;
}
```

À la fin du script, le destructeur affiche :

```text
Détruire Personne
```

La constante magique `__CLASS__` retourne le nom de la classe dans laquelle elle est utilisée.

---

# Partie 3 – Surcharge de propriétés

### 3.1 – Compléter les quatre méthodes magiques

```php
public function __set(string $name, mixed $value): void {
    if (property_exists($this, $name)) {
        $this->$name = $value;
    } else {
        echo "Propriété $name inexistante" . PHP_EOL;
    }
}

public function __get(string $name): mixed {
    if (property_exists($this, $name)) {
        return $this->$name;
    }

    return null;
}

public function __isset(string $name): bool {
    return property_exists($this, $name) && isset($this->$name);
}

public function __unset(string $name): void {
    if (property_exists($this, $name)) {
        $this->$name = "";
    }
}
```

Résultats attendus :

- `__set` permet de modifier une propriété privée.
- `__get` permet de lire une propriété privée.
- `__isset` vérifie si une propriété est définie et non nulle.
- `__unset` remet la propriété à une chaîne vide.

### 3.2 – Sans `__set`, que se passe-t-il ?

Sans `__set`, l'accès à une propriété privée depuis l'extérieur provoque une erreur. Avec `__set`, on peut intercepter cette opération et contrôler la modification de la propriété.

### 3.3 – `__set` peut-il casser l'encapsulation ?

Oui, si aucune validation n'est effectuée, `__set` peut permettre de modifier les propriétés sans respecter les règles de la classe. Il faut donc vérifier les valeurs avant de les affecter.

### 3.4 – Ajouter une validation du nom

```php
public function __set(string $name, mixed $value): void {
    if (!property_exists($this, $name)) {
        echo "Propriété $name inexistante" . PHP_EOL;
        return;
    }

    if ($name === "nom") {
        if (!is_string($value) || strlen($value) < 2) {
            throw new InvalidArgumentException("Le nom doit contenir au moins 2 caractères.");
        }
    }

    $this->$name = $value;
}
```

---

# Partie 4 – Représentation textuelle : __toString

### Code de la méthode `__toString()`

```php
public function __toString(): string {
    return $this->prenom . " " . $this->nom . " (" . $this->getAge() . " ans)";
}
```

### 4.1 – Pourquoi `getAge()` fonctionne-t-elle ici ?

Parce que `__toString()` et `getAge()` appartiennent à la même classe. Une méthode privée peut être appelée par les autres méthodes de cette classe.

### 4.2 – Que se passe-t-il si `__toString()` retourne un entier ?

U**### 4.2 – Que se passe-t-il si `__toString()` retourne un entier ?**

Dans notre cas, PHP convertit automatiquement l'entier en chaîne de caractères, car le typage strict n'est pas activé. La valeur affichée est donc `123`.
Avec strict_types=1, une erreur TypeError se produit.

Avec `strict_types=1`, une erreur `TypeError` se produit.

### 4.3 – Quel est l'intérêt de `__toString()` ?

`__toString()` permet d'afficher directement un objet avec `echo`, sans appeler explicitement une méthode comme `getName()`.

Deux situations pratiques :

1. Afficher directement les informations d'une personne.
2. Afficher les objets dans une boucle ou dans une liste.

---

# Partie 5 – Sérialisation

### 5.1 – Le mot de passe figure-t-il dans la chaîne sérialisée ?

Non, le mot de passe ne figure pas dans la chaîne sérialisée, car il est volontairement exclu dans la méthode `__serialize()`.

### 5.2 – Mettre `__serialize()` en commentaire

Sans `__serialize()`, PHP sérialise automatiquement les propriétés de l'objet, y compris la propriété privée `motDePasse`.

Cela représente un risque de sécurité, car des informations sensibles peuvent être stockées ou exposées.

### 5.3 – Quelle est la valeur de `$motDePasse` après unserialize ?

Après `unserialize()`, la valeur de `$motDePasse` est une chaîne vide `""`, car elle est réinitialisée dans la méthode `__unserialize()`.

Dans une vraie application, il faudrait prévoir une méthode sécurisée pour vérifier ou réinitialiser le mot de passe.

### 5.4 – Pourquoi ne faut-il jamais utiliser unserialize() sur des données utilisateur ?

Il ne faut jamais utiliser `unserialize()` sur des données provenant directement de l'utilisateur, car cela peut provoquer une attaque appelée **PHP Object Injection**.

Une alternative plus sûre pour échanger des données simples est d'utiliser `json_encode()` et `json_decode()`.

---

# Partie 6 – Héritage

## 6.1 – Découverte avec Animal et Chien

### 6.1.a – Ajouter une classe Chat

```php
class Chat extends Animal {
    public function miauler(): void {
        echo "Miaou !" . PHP_EOL;
    }
}
```

### 6.1.b – Peut-on appeler `$c->miauler()` ?

Non, car `$c` est un objet de la classe `Chien`. La méthode `miauler()` appartient à la classe `Chat`, et non à `Chien` ou `Animal`.

## 6.2 – parent:: et redéfinition

### 6.2.a – Supprimer `parent::__construct($nom)`

**### 6.2.a – Supprimer `parent::__construct($nom)`**

Le constructeur de la classe mère n'est plus appelé. La propriété typée `$nom` n'est donc pas initialisée. Lorsqu'on appelle `presentation()`, PHP déclenche une erreur, car cette propriété est utilisée avant son initialisation.

### 6.2.b – Pourquoi `$nom` est-il protected et non private ?

L'attribut `$nom` est `protected` pour permettre à la classe fille `Etudiant` d'y accéder directement. Avec `private`, cet attribut serait accessible uniquement dans la classe `Personne`.

## 6.3 – final et readonly

### 6.3.a – Les trois erreurs

1. Redéfinir la méthode `afficher()` dans `Fille` provoque une erreur, car cette méthode est déclarée `final`.
2. Créer `SousOutil extends Outil` provoque une erreur, car la classe `Outil` est déclarée `final`.
3. Modifier `$p->reference` provoque une erreur, car la propriété est déclarée `readonly` et ne peut plus être modifiée après son initialisation.

**Remarque :** La version PHP 8.0.30 installée sur notre ordinateur ne prend pas en charge les propriétés `readonly`. Pour tester cette fonctionnalité, il faut utiliser PHP 8.1 ou une version plus récente.
### 6.3.b – Exemples réels

- `readonly` : un identifiant ou une date de création qui ne doit pas changer après l'initialisation.
- `final` : une méthode de sécurité qui ne doit pas être redéfinie dans une classe fille.

## 6.4 – Exercice principal : Etudiant extends Personne

### Code de la classe Etudiant

```php
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
        return parent::__toString() . " | CNE : " . $this->cne;
    }
}
```

### Créer cinq étudiants et les afficher

```php
$etudiants = [];

$etudiants[] = new Etudiant("Hachimi", "Sara", "2000-05-14", "Marrakech", "EE123456", "CNE001");
$etudiants[] = new Etudiant("Boucetta", "Somya", "2006-03-10", "Zagora", "EE123457", "CNE002");
$etudiants[] = new Etudiant("Amrani", "Ahmed", "2004-01-20", "Rabat", "EE123458", "CNE003");
$etudiants[] = new Etudiant("Fassi", "Salma", "2005-06-15", "Fès", "EE123459", "CNE004");
$etudiants[] = new Etudiant("Idrissi", "Youssef", "2003-09-12", "Casablanca", "EE123460", "CNE005");

foreach ($etudiants as $e) {
    echo $e . PHP_EOL;
}
```

### 6.4.a – Appeler `$this->getAge()` depuis Etudiant

Un problème d'accès se produit, car `getAge()` est privée dans `Personne`. Pour permettre son utilisation dans la classe fille, il faut remplacer `private` par `protected`.

### 6.4.b – Accéder à `$this->dateNaissance`

Cela est impossible, car `dateNaissance` est déclarée `private` dans `Personne`. Les propriétés privées ne sont accessibles que dans leur classe d'origine.

### 6.4.c – Avantage de `parent::__toString()`

Cela permet de réutiliser le code de la classe mère et d'éviter de répéter les mêmes instructions. Le code devient plus simple et plus facile à maintenir.

---

# Partie 7 – Mise en pratique : le compte bancaire

### 7.1 – Repérer l'erreur de syntaxe

**### 7.1 – Repérer l'erreur de syntaxe**

L'erreur se trouve dans l'expression de la méthode `un_an()`. Il manque un opérateur entre `$this->montant` et `1`, ce qui provoque une erreur de syntaxe.

### 7.2 – Écrire la bonne formule

La formule correcte est :

\[
M_{\text{nouveau}} = M_{\text{initial}} \times \left(1+\frac{t}{100}\right)
\]

### 7.3 – L'ordre des paramètres du constructeur

Non, l'ordre des paramètres n'est pas cohérent avec l'énoncé. L'énoncé demande d'abord le montant initial, puis le taux d'intérêt.

Le risque est d'inverser les valeurs lors de la création des objets.

### 7.4 – Code corrigé avec les types

```php
<?php

class Compte {
    private float $montant;
    private float $interet;

    public function __construct(float $montantInitial, float $interet) {
        $this->montant = $montantInitial;
        $this->interet = $interet;
    }

    public function un_an(): void {
        $this->montant = $this->montant * (1 + $this->interet / 100);
    }

    public function get_montant(): float {
        return $this->montant;
    }
}

$c1 = new Compte(400, 10);
$c2 = new Compte(2500, 5);

for ($i = 1; $i <= 10; $i++) {
    $c1->un_an();
    $c2->un_an();
}

echo "Valeur du compte = " . number_format($c1->get_montant(), 2, ',', ' ') . " dirhams" . PHP_EOL;

echo "Valeur du compte = " . number_format($c2->get_montant(), 2, ',', ' ') . " dirhams" . PHP_EOL;
```

Résultats attendus après 10 ans :

- Compte `$c1` : environ 1 037,50 DH.
- Compte `$c2` : environ 4 072,24 DH.

---

# Questions de synthèse

### 1. Quelle est la différence entre une classe et un objet ?

Une classe est un modèle qui définit les propriétés et les méthodes. Un objet est une instance concrète créée à partir de cette classe.

### 2. Citez trois avantages de la POO.

1. La réutilisation du code grâce à l'héritage.
2. L'encapsulation qui protège les données.
3. Une meilleure organisation et maintenance du programme.

### 3. Pourquoi déclarer les attributs en private ou protected ?

Pour protéger les données et contrôler leur accès et leur modification.

### 4. Quelle différence entre private et protected ?

- `private` : accessible uniquement dans la classe elle-même.
- `protected` : accessible dans la classe elle-même et dans ses classes filles.

### 5. Une méthode magique doit-elle être déclarée public ?

**### 5. Une méthode magique doit-elle être déclarée public ?**

En général, les méthodes magiques doivent être déclarées `public`. Les exceptions tolérées sont notamment `__construct()`, `__destruct()` et `__clone()`, qui peuvent avoir une visibilité différente selon les besoins. 