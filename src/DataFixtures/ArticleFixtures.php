<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");
        // creation de 3 Categorys
        for($i = 0; $i <= 3; $i++){
            $category = new Category();
                $category->setTitle($faker->sentence())
                        ->setContent($faker->paragraph());
            $manager->persist($category);
            //creation entre 4 et 6 articles
            for($j = 0; $j <= mt_rand(4, 6); $j++){
                $article = new Article();
                $content = "<p>".join($faker->paragraphs(5), '<p></p>')."</p>";
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);
                //creation entre 4 et 10 commentaires
                for($k = 0; $k < mt_rand(4, 10); $k++){
                    $comment = new Comment();
                    $content = "<p>".join($faker->paragraphs(3), '<p></p>')."</p>";
                    $days=(new \DateTime())->diff($article->getCreatedAt())->days;
                    $comment->setAuthor($faker->name)
                            ->setComment($content)
                            ->setCreatedAt($faker->dateTimeBetween('-'.$days.' days'))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        

        $manager->flush();
    }
}
