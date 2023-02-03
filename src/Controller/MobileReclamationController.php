<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponsereclamation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Serializer;
use Lcobucci\JWT\Exception;

use App\Entity\Users;
class MobileReclamationController extends AbstractController
{


    /**
     * @Route("/displayReclamationMobile", name="displayreclamationMobile")
     */
    public function displayReclamationMobile( SerializerInterface $serializer): Response
    {

        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $formatted = $serializer->normalize($reclamation,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addReclamationMobile", name="addReclamationMobile")
     */
    public function addReclamationMobile(Request $request, SerializerInterface $serializer,MailerInterface $mailer): Response
    {

        $typeR = new Reclamation();
        $entityManager = $this->getDoctrine()->getManager();


        $datereclamation=$request->query->get('dateReclamation');

        $typeR->setNom($request->get('nom')) ;
        $typeR->setPrenom($request->get('prenom')) ;
        $typeR->setEmail($request->get('email')) ;
        $typeR->setCommentaire($request->get('commentaire')) ;
        $typeR->setTypereclamation($request->get('typeReclamation')) ;
        $typeR->setDatereclamation(new \DateTime($datereclamation)) ;
        $email = (new  TemplatedEmail())
            ->from('khitem.mathlouthi@sprittn ')
            ->to('pegasustravels10@gmail.com')
            ->htmlTemplate('reclamation/maile.html.twig')
            ->subject('🥳 Une nouvelle reclamation est organisé 🥳');




        $mailer->send($email);
   // $id=$request->get('id');
     //  $typeR->setId($id);



        $entityManager->persist($typeR);
        $entityManager->flush();

        $formatted = $serializer->normalize($typeR,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/deleteReclamationMobile", name="deleteReclamationMobile")
     */
    public function deleteReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {
        $numero=$request->query->get("numero") ;
        $entityManager = $this->getDoctrine()->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find( $numero);
        if($reclamation!=null){
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $formatted = $serializer->normalize($reclamation,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" type invalide") ;
    }
    /**
     * @Route("/updateReclamationMobile", name="updateReclamationMobile")
     */
    public function updateReclamationMobile(Request $request,SerializerInterface $normalizer): Response{

        $numero = $request->query->get("numero");
        $entityManager = $this->getDoctrine()->getManager();
        $typeR= $entityManager->getRepository(Reclamation::class)->find($numero);
        $typeR->setNom($request->get('nom')) ;
        $typeR->setPrenom($request->get('prenom')) ;
        $typeR->setEmail($request->get('email')) ;
        $typeR->setCommentaire($request->get('commentaire')) ;
        $typeR->setTypereclamation($request->get('typeReclamation')) ;
        $dateReclamation = $request->query->get('dateReclamation');
        $typeR->setDatereclamation(new \DateTime($dateReclamation)) ;

        $formatted = $normalizer->normalize($typeR, 'json', ['groups' => 'post:read']);
        return new Response("Reclamation a été modifier ".json_encode($formatted));

    }

    /**
     * @Route("/updateReclamation", name="update_reclamation")

     */
    public function modifier(Request $request, NormalizerInterface$normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $typeR = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->find($request->get("numero"));

        $typeR->setNom($request->get('nom')) ;
        $typeR->setPrenom($request->get('prenom'));
        $typeR->setEmail($request->get('email'));
        $typeR->setCommentaire($request->get('commentaire'));
        $typeR->setTypereclamation($request->get('typereclamation'));
        $datereclamation= $request->query->get('datereclamation');
        $typeR->setDatereclamation(new \DateTime($datereclamation));

        $em->persist($typeR);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($typeR);
        return new Response(json_encode($formatted)) ;

    }

    }
