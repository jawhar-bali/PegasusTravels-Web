<?php

namespace App\Controller;

use App\Entity\Users;
use mysql_xdevapi\Exception;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\SecurityBundle\Command\UserPasswordEncoderCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserControllermobileController extends AbstractController
{
    /**
     * @Route("/displayuserMobile", name="displayuserMobile")
     */
    public function displayuserMobile(SerializerInterface $Serializer): Response
    {
        $user = $this->getDoctrine()->getRepository(Users::class)->findAll();
        $formatted = $Serializer->normalize($user,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }

    /**
     * @Route("/adduserMobile", name="adduserMobile")
     */
    public function adduserMobile(Request $request, SerializerInterface $normalizer): Response
    {
        $user = new Users();
        $entityManager = $this->getDoctrine()->getManager();
        $user->setNom($request->get('nom'));
        $user->setprenom($request->get('prenom'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));

        $entityManager->persist($user);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("user/signup", name="register")
     */
    public function signupAction(Request $request, UserPasswordEncoderInterface $passwordencoder)
    {

      //$nom = $request->get("nom");
       //$prenom = $request->get("prenom");
      // $email = $request->get("email");
      // $password = $request->get("password");
      // $roles = $request->get("roles");

        //if(!filter_has_var($email, FILTER_VALIDATE_EMAIL)){
            //return new Response("email invalide");
       // }


        try{

            return new JsonResponse("account is created",200);

        }catch(\Exception $ex){
            return new Response("exception".$ex->getMessage());

        }

    }

    /**
     * @Route("user/signin", name="login")
     */
  public function signinAction(Request $request){
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->findOneBy(['email'=>$email]);
        if($user){
            if(password_verify($password,$user->getPassword() )){
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }else{
                return new Response('Password is invalid');
            }

        }else{
            return new Response('User not found please try again');

        }

    }

    /**
     * @Route("user/edituser", name="editprofile")
     */
    public function edituser(Request $request, UserPasswordEncoderInterface $passwordencoder){
        $id = $request->get("id");
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $nom = $request->query->get("nom");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Users::class)->find($id);
        $user->setEmail($email);
        $user->setPassword(
            $passwordencoder->encodePassword(
                $user,
                'password'
            )
        );
        $user->setNom($nom);
        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("account edited",200);

        }catch(\Exception $ex){
            return new Response("failed".$ex->getMessage());

        }



    }

    /**
     * @Route("/deleteuserMobile", name="deleteuserMobile")
     */
    public function deleteuserMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->query->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $user= $entityManager->getRepository(Users::class)->find($id);
        if ($user != null) {
            $entityManager->remove($user);
            $entityManager->flush();
            $formatted = $serializer->normalize($user, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($formatted));

        }


        return new Response(" type invalide");
    }
    /**
     * @Route("/updateuserMobile", name="updateuserMobile")
     */
    public function updateuserMobile(Request $request,SerializerInterface $normalizer): Response{

        $em = $this->getDoctrine()->getManager();
        $User= $this->getDoctrine()->getManager()
            ->getRepository(Users::class)
            ->find($request->get("id"));

        $User->setNom($request->get("nom"));
        $User->setprenom($request->get("prenom"));
        $User->setEmail($request->get('email'));
        $User->setPassword($request->get('password'));

        $em->persist($User);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($User);
        return new JsonResponse("User a ete modifiee avec success.");
    }

}
