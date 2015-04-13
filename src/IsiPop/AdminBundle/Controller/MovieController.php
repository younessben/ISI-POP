<?php

namespace IsiPop\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \IsiPop\AdminBundle\Entity\Movie;

class MovieController extends Controller
{
    public function DefaultAction()
    {
         return $this->render('IsiPopAdminBundle:Default:frenchHome.html.twig');
    }
    
    public function AddAction(Request $request) {
          
        $movie = new Movie();
        //$form = $this->createFormBuilder()
        $form = $this->get('form.factory')->createBuilder('form', $movie)
                ->add('title', 'text')
                ->add('urlMovie', 'text')
                ->add('imdbCode', 'text')
                ->add('urlCover', 'text')
                ->add('Add', 'submit')
                ->getForm(); 
                    $form->handleRequest($request);

                      // On vérifie que les valeurs entrées sont correctes
                   // (Nous verrons la validation des objets en détail dans le prochain chapitre)
                   if ($form->isValid()) {
                      /* $movie->setTitle("tumbutu");
                       $movie->setImdbCode("t1258");
                       $movie->setUrlCover("www.popcorn.fr");
                       $movie->setUrlMovie("www.popcorn.fr/cover");*/
                     // On l'enregistre notre objet $advert dans la base de données, par exemple
                    $em = $this->getDoctrine()->getManager();
                     $em->persist($movie);
                     $em->flush();
                    
                     $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                       
                     // On redirige vers la page de visualisation de l'annonce nouvellement créée
                     
                      $movie = $this->getDoctrine()
                
                     ->getManager()
                     ->getRepository('IsiPopAdminBundle:Movie');
                         $movies = $movie->findAll();
                        // var_dump($movies);
                         return $this->render('IsiPopAdminBundle:Default:show.html.twig', array('movies' => $movies));
                   //  return $this->render('IsiPopAdminBundle:Default:validationAdd.html.twig', array('id' => $movie));
                     
                     
               }
        return $this->render('IsiPopAdminBundle:Default:Add.html.twig', array(
                    'name' => $form->createView()
             
        ));
      
    }
    
    
      public function UpdateAction($id , Request $request) {

        // Récupération d'une annonce déjà existante, d'id $id.
            $movie = $this->getDoctrine()
                
              ->getManager()
              ->getRepository('IsiPopAdminBundle:Movie')
              ->find($id);
            
            $formBuilder = $this->get('form.factory')->createBuilder('form', $movie)
                ->add('title', 'text')
                ->add('urlMovie', 'text')
                ->add('imdbCode', 'text')
                ->add('urlCover', 'text')
                ->add('Add', 'submit')
                ->getForm(); 
            
             $formBuilder->handleRequest($request);

                      // On vérifie que les valeurs entrées sont correctes
                   // (Nous verrons la validation des objets en détail dans le prochain chapitre)
                   if ($formBuilder->isValid()) {
                      /* $movie->setTitle("tumbutu");
                       $movie->setImdbCode("t1258");
                       $movie->setUrlCover("www.popcorn.fr");
                       $movie->setUrlMovie("www.popcorn.fr/cover");*/
                     // On l'enregistre notre objet $advert dans la base de données, par exemple
                    $em = $this->getDoctrine()->getManager();
                     $em->persist($movie);
                     $em->flush();
                    
                     $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                       echo 'Modification terminée';
                     // On redirige vers la page de visualisation de l'annonce nouvellement créée
                    // return $this->render('ISIPOPPHPmainBundle:Default:validationAdd.html.twig', array('id' => $movie));
               }
               
               
             return $this->render('IsiPopAdminBundle:Default:Update.html.twig', array(
                    'name' => $formBuilder->createView()
             
        ));
    }
    
      public function DeleteAction($id) {
          
                $em = $this->getDoctrine()->getManager();
                $movie = $em->getRepository('IsiPopAdminBundle:Movie')->find($id);
                $em->remove($movie);
                $em->flush();
                
          return $this->redirect($this->generateUrl('isipopph_pmain_default'));
    }
    
    public function ShowAction()
    {
             $movie = $this->getDoctrine()
                
              ->getManager()
              ->getRepository('IsiPopAdminBundle:Movie');
              $movies = $movie->findAll();
             // var_dump($movies);
              return $this->render('IsiPopAdminBundle:Default:show.html.twig', array('movies' => $movies));
            
    }
}
