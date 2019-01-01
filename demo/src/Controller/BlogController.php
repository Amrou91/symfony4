<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Article;
use App\Entity\Category;
class BlogController extends AbstractController
{
    
      /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig',[
            'title'=>"Welcom",
            'age'=>35
        ]);
    }
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo=$this->getDoctrine()->getRepository(Article::class);

        $articles= new Article();

        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig',[
            'articles'=>$articles
        ]);
    }
     /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function form(Article $article= null, Request $request, ObjectManager $manager){
       if(!$article){
            $article= new Article();
       }
        $form=$this->createFormBuilder($article)
                   ->add('title')
                   ->add('category',EntityType::class,[
                       'class'=>Category::class,
                       'choice_label'=>'title'
                   ])
                   ->add('content')
                   ->add('image')
                   ->getForm();
        $form->handleRequest($request);
        // verification de la soumiussion du form et si les données sont valid
        if($form->isSubmitted() && $form->isValid()){
             if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
              }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute("blog_show",['id'=>$article->getId()]);
        }
        return $this->render('blog/create.html.twig',[
            'formArticle'=>$form->createView(),
            'editMod'=>$article->getId()
        ]);
    }
    /**
     * @Route("blog/{id}", name="blog_show")
     */
    public function show($id){
        $article= new Article();
        $repo=$this->getDoctrine()->getRepository(Article::class);
        $article=$repo->find($id);
        return $this->render('blog/show.html.twig',[
            'article'=>$article
        ]);
    }
   
}
