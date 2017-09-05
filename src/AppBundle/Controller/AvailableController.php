<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Available;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use \Datetime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
/**
 * Available controller.
 *
 * @Route("available")
 */
class AvailableController extends Controller
{
    /**
     * Lists all available entities.
     *
     * @Route("/", name="available_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $availables = $em->getRepository('AppBundle:Available')->findAll();

        return $this->render('available/index.html.twig', array(
            'availables' => $availables,
        ));
    }

    /**
     * Creates a new available entity.
     *
     * @Route("/new", name="available_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $helpers->authCheck($hash, true);

            $json = $request->get("json", null);

            if ($json != null) {
                $params = json_decode($json);


                $user_id = ($identity->id != null) ? $identity->id : null;
                $day = (isset($params->day)) ? $params->day : null;
                $startam = (isset($params->startam)) ? $params->startam : null;
                $endam = (isset($params->endam)) ? $params->endam : null;
                $startpm = (isset($params->statpm)) ? $params->startpm : null;
                $endpm = (isset($params->endpm)) ? $params->endpm : null;
                $option_id = (isset($params->option)) ? $params->option: null;



                if ( $day != null &&    $user_id != null ) {
                    $em = $this->getDoctrine()->getManager();

                    $Option= $em->getRepository("AppBundle:Options")->findOneBy(
                        array(
                            "id" => $option_id
                        ));


                    $Available = new Available();
                    $Available->setDay($day);
                    $Available->setStartAm(new \DateTime($startam));
                    $Available->setEndAm(new \DateTime($endam));
                    $Available->setStartPm(new \DateTime($startpm));
                    $Available->setEndPm(new \DateTime($endpm));
                    $Available->setOptions($Option);


                    $em->persist($Available);
                    $em->flush();


                    $repository = $em->getRepository("AppBundle:Available");

                    $query = $repository->createQueryBuilder('p')
                        ->select(array(
                                'p.id as codigo',
                                'p.day',
                                'p.startPm',
                                'p.endPm',
                                'p.startAm',
                                'p.endAm',
                            )
                        )
                        ->where('p.id = :idcompany')
                        ->setParameter(':idcompany',$Available->getId())
                        ->setMaxResults(1)
                    ;
                    $available=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
//                    return new JsonResponse($query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));


                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $available
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "available not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "available not created, params failed"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }

    /**
     * Finds and displays a available entity.
     *
     * @Route("/{id}", name="available_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id = null)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();


        $repository = $em->getRepository("AppBundle:Available");

        $query = $repository->createQueryBuilder('p')
            ->select(array(
                    'p'
                )
            )
            ->where('p.id = :idavailable')
            ->setParameter(':idavailable',$id)
            ->setMaxResults(1)
        ;
        $company=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if(count($company) >= 1){
            $data = array(
                "status" => "success",
                "code"	 => 200,
                "data"	 => $company
            );
        }else{
            $data = array(
                "status" => "error",
                "code"	 => 400,
                "msg"	 => "Dont exists this Available!!"
            );
        }

        return $helpers->json($data);
    }

    /**
     * Displays a form to edit an existing available entity.
     *
     * @Route("/{id}/edit", name="available_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request,  $id = null)
    {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $helpers->authCheck($hash, true);

            $json = $request->get("json", null);

            if ($json != null) {
                $params = json_decode($json);


                $user_id = ($identity->id != null) ? $identity->id : null;
                $day = (isset($params->day)) ? $params->day : null;
                $startam = (isset($params->startam)) ? $params->startam : null;
                $endam = (isset($params->endam)) ? $params->endam : null;
                $startpm = (isset($params->statpm)) ? $params->startpm : null;
                $endpm = (isset($params->endpm)) ? $params->endpm : null;
                $option_id = (isset($params->option)) ? $params->option: null;



                if ( $day != null &&    $user_id != null ) {
                    $em = $this->getDoctrine()->getManager();

                    $Option= $em->getRepository("AppBundle:Options")->findOneBy(
                        array(
                            "id" => $option_id
                        ));

                    $Available = $em->getRepository("AppBundle:Available")->findOneBy(
                        array(
                            "id" => $id
                        ));




                    $Available->setDay($day);
                    $Available->setStartAm(new \DateTime($startam));
                    $Available->setEndAm(new \DateTime($endam));
                    $Available->setStartPm(new \DateTime($startpm));
                    $Available->setEndPm(new \DateTime($endpm));
                    $Available->setOptions($Option);



                    $em->persist($Available);
                    $em->flush();


                    $repository = $em->getRepository("AppBundle:Available");

                    $query = $repository->createQueryBuilder('p')
                        ->select(array(
                                'p.id as codigo',
                                'p.day',
                                'p.startPm',
                                'p.endPm',
                                'p.startAm',
                                'p.endAm',
                            )
                        )
                        ->where('p.id = :idcompany')
                        ->setParameter(':idcompany',$Available->getId())
                        ->setMaxResults(1)
                    ;
                    $available=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
//                    return new JsonResponse($query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));


                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $available
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "available not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "available not created, params failed"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }

    /**
     * Deletes a available entity.
     *
     * @Route("/{id}", name="available_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Available $available)
    {
        $form = $this->createDeleteForm($available);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($available);
            $em->flush();
        }

        return $this->redirectToRoute('available_index');
    }

    /**
     * Creates a form to delete a available entity.
     *
     * @param Available $available The available entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Available $available)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('available_delete', array('id' => $available->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
