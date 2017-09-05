<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use \Datetime;

/**
 * Option controller.
 *
 * @Route("options")
 */
class OptionsController extends Controller
{
    /**
     * Lists all option entities.
     *
     * @Route("/", name="options_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $options = $em->getRepository('AppBundle:Options')->findAll();

        return $this->render('options/index.html.twig', array(
            'options' => $options,
        ));
    }

    /**
     * Creates a new option entity.
     *
     * @Route("/new", name="options_new")
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

                $name = (isset($params->name)) ? $params->name : null;
                $duration = (isset($params->duration)) ? $params->duration : null;
                $photo = null;
                $status="Active";


                $time= new DateTime($duration);
                
                $createdAt = new \Datetime('now');
                $updatedAt = new \Datetime('now');



                if ( $name != null   &&  $user_id != null && $duration != null ) {
                    $em = $this->getDoctrine()->getManager();

                    $Company= $em->getRepository("AppBundle:Company")->findOneBy(
                        array(
                            "user" => $user_id
                        ));


                    $Options = new Options();
                    $Options->setName($name);
                    $Options->setPhoto($photo);
                    $Options->setDuration($time);
                    $Options->setCreateAt($createdAt);
                    $Options->setUpdateAt($updatedAt);
                    $Options->setStatus($status);
                    $Options->setCompany($Company);


                    $em->persist($Options);
                    $em->flush();

                    $Options=array(
                        "id"=>$Options->getId(),
                        "name"=>$name,
                        "photo"=>$photo,
                        "duration"=>$duration,
                        "company"=>$Options->getCompany()->getName()
                        
                    );

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $Options
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Options not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Option not created, params failed"
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
     * Finds and displays a option entity.
     *
     * @Route("/{id}", name="options_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id = null)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $Options = $em->getRepository("AppBundle:Options")->findOneBy(array(
            "id" => $id
        ));

//        $comments = $em->getRepository("AppBundle:User")->findBy(array(
//           "id" => $company->getUser()->getId()
//        ), array('id'=>'desc'));



      
        if(count($Options) >= 1){
            $Options=array(
                "id"=>$Options->getId(),
                "name"=>$Options->getName(),
                "duraton"=>$Options->getDuration(),
                "photo"=>$Options->getPhoto(),
                "company"=>$Options->getCompany()->getName()
    
            );
            $data = array(
                "status" => "success",
                "code"	 => 200,
                "data"	 => $Options
            );
        }else{
            $data = array(
                "status" => "error",
                "code"	 => 400,
                "msg"	 => "Dont exists this Options!!"
            );
        }

        return $helpers->json($data);
    }

    /**
     * Displays a form to edit an existing option entity.
     *
     * @Route("/{id}/edit", name="options_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id = null)
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

                $name = (isset($params->name)) ? $params->name : null;
                $duration = (isset($params->duration)) ? $params->duration : null;
                $status = (isset($params->status)) ? $params->status : null;
                
                $photo = null;
                $time= new DateTime($duration);
                $option_id = $id;
                
                $updatedAt = new \Datetime('now');

                if ( $name != null   &&  $user_id != null && $duration != null && $status != null ) {
                    $em = $this->getDoctrine()->getManager();

                    $Options= $em->getRepository("AppBundle:Options")->findOneBy(
                        array(
                            "id" => $option_id
                        ));


                    $Options->setName($name);
                    $Options->setPhoto($photo);
                    $Options->setDuration($time);
                    $Options->setUpdateAt($updatedAt);
                    $Options->setStatus($status);

                    $em->persist($Options);
                    $em->flush();

                    $Options=array(
                        "id"=>$Options->getId(),
                        "name"=>$name,
                        "photo"=>$photo,
                        "duration"=>$duration,
                        "status"=>$status,                        
                        "company"=>$Options->getCompany()->getName()
                        
                    );

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $Options
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Options not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Option not created, params failed"
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
     * Deletes a option entity.
     *
     * @Route("/{id}/delete", name="options_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id = null)
    {
        $helpers = $this->get("app.helpers");
        
        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {

            $identity = $helpers->authCheck($hash, true);

            $user_id = ($identity->id != null) ? $identity->id : null;

            $em = $this->getDoctrine()->getManager();

            $Option = $em->getRepository("AppBundle:Options")->findOneBy(array(
                "id" => $id
            ));

            if (is_object($Option) && $user_id != null) {
                if (isset($identity->id) &&
                    ($identity->id == $Option->getCompany()->getUser()->getId())) {

                    $em->remove($Option);
                    $em->flush();

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "msg" => "Option deleted success"
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Unauthorized permission"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Options not deleted"
                );
            }
        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Authencation not valid"
            );
        }
        return $helpers->json($data);
    }


    /**
     * Creates a new option entity.
     *
     * @Route("/listoptions", name="options_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {
        $helpers = $this->get("app.helpers");

        $hash = $request->get("authorization", null);
        $authCheck = $helpers->authCheck($hash);

        if ($authCheck == true) {
            $identity = $helpers->authCheck($hash, true);

            $json = $request->get("json", null);




            $user_id = ($identity->id != null) ? $identity->id : null;


            if (  $user_id != null ) {
                $em = $this->getDoctrine()->getManager();

                $Company= $em->getRepository("AppBundle:Company")->findOneBy(
                    array(
                        "user" => $user_id
                    ));


                $repository = $em->getRepository("AppBundle:Options");

                $query = $repository->createQueryBuilder('p')
                    ->select(array(
                            'p.name'
                        )
                    )
                    ->where('p.company = :idcompany')
                    ->setParameter(':idcompany',$Company->getId())
//                    ->setMaxResults(100)
                ;
                $Options=$query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);


                $data = array(
                    "status" => "success",
                    "code" => 200,
                    "data" => $Options
                );
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Options not created"
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

   
}
