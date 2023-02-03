<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evenement;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class MobileEventController extends AbstractController
{
    /**
     * @Route("/displayEvenementMobile", name="displayevenementMobile")
     */
    public function displayEvenementMobile(SerializerInterface $Serializer): Response
    {
        $reclamation = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $formatted = $Serializer->normalize($reclamation,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }

    /**
     * @Route("/addEvenementMobile", name="addevenementMobile")
     */
    public function addEvenementMobile(Request $request, NormalizerInterface $normalizer): Response
    {
        $Event = new Evenement();
        $entityManager = $this->getDoctrine()->getManager();
        $Event->setNomevent($request->get('nomevent'));
        $prix = (float)$request->query->get('prixevent');
        $Event->setPrixevent($prix);
        $date = $request->query->get('date');
        $Event->setDate(new \DateTime($date));
        $entityManager->persist($Event);
        $entityManager->flush();
        $jsonContent = $normalizer->normalize($Event, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/deleteEvenementMobile", name="deleteEvenementMobile")
     */
    public function deleteReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id = $request->query->get("idevent");
        $entityManager = $this->getDoctrine()->getManager();
        $evenement= $entityManager->getRepository(Evenement::class)->find($id);
        if ($evenement != null) {
            $entityManager->remove($evenement);
            $entityManager->flush();
            $formatted = $serializer->normalize($evenement, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($formatted));

        }


        return new Response(" type invalide");
    }
    /**
     * @Route("/updateEvenementMobile", name="updateEvenementMobile")
     */
    public function updateReclamationMobile(Request $request,NormalizerInterface $normalizer): Response{

        $em = $this->getDoctrine()->getManager();
        $Event = $this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find($request->get("idevent"));

        $Event->setPrixevent($request->get("prixevent"));
        $Event->setNomevent($request->get("nomevent"));
        $date = $request->query->get('date');
        $Event->setDate(new \DateTime($date));

        $em->persist($Event);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Event);
        return new Response(json_encode($formatted));


    }

}
