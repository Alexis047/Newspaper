<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Cinéma',
            'Sport',
            'People',
            'Musique',
            'Sciences Technologie',
            'Politique',
            'Jeux vidéo',
            'Ecologie',
            'Mode'
        ];

        foreach($categories as $name) {

            $category = new Category();

            $category->setName($name);
            $category->setAlias($this->slugger->slug($name));

            $category->setCreatedAt(new DateTime());
            $category->setUpdatedAt(new DateTime());

            # La méthode persist() exécute les requêtes SQL en BDD, enfin il les stocke plutôt.
            $manager->persist($category);
        }

        # La méthode flush() n'est pas dans la boucle foreach() pour une raison :
        # => cette méthode "vide" l'objet $manager qui est un 'container'.
        # Avant de se "vider", le $manager exécute les insertions en BDD, pour de vrai cette fois-ci !
        $manager->flush();
    }
}
