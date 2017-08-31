<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
/**
 * Company controller.
 *
 * @Route("company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all company entities.
     *
     * @Route("/", name="company_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository('AppBundle:Company')->findAll();

        return $this->render('company/index.html.twig', array(
            'companies' => $companies,
        ));
    }

    /**
     * Creates a new company entity.
     *
     * @Route("/new", name="company_new")
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

                $createdAt = new \Datetime('now');
                $updatedAt = new \Datetime('now');


                $user_id = ($identity->id != null) ? $identity->id : null;
                $name = (isset($params->name)) ? $params->name : null;
                $description = (isset($params->description)) ? $params->description : null;
                $address = (isset($params->address)) ? $params->address : null;
                $phone = (isset($params->phone)) ? $params->phone : null;
                $titledescription = (isset($params->titledescription)) ? $params->titledescription : null;
                $photoavatar = null;
                $photoheader = null;

//
                if ( $name != null &&  $description != null &&  $user_id != null ) {
                    $em = $this->getDoctrine()->getManager();

                    $user = $em->getRepository("AppBundle:User")->findOneBy(
                        array(
                            "id" => $user_id
                        ));

                    $Company = new Company();
                    $Company->setName($name);
                    $Company->setTitledescription($titledescription);
                    $Company->setDescription($description);
                    $Company->setPhone($phone);
                    $Company->setAddress($address);
                    $Company->setPhotoavatar($photoavatar);
                    $Company->setPhotoheader($photoheader);
                    $Company->setUser($user);

                    $em->persist($Company);
                    $em->flush();

                    $company=array(
                        "id"=>$Company->getId(),
                        "name"=>$name,
                        "title"=>$titledescription,
                        "description"=>$description,
                        "phone"=>$phone,
                        "address"=>$address,
                        "photoheader"=>$photoheader,
                        "photoavatar"=>$photoavatar,
                        "id_user"=>$user_id
                    );

                   // $Company = $em->getRepository("AppBundle:Company")->findOneById($Company->getId());

//
//                    $Company = $em->getRepository("AppBundle:Company")->findOneBy(
//                        array(
//                            "id" =>$Company->getId(),
//                        ),array('name' => 'ASC'),1,0);


                   $serializer = SerializerBuilder::create()->build();

                    $jsonObject = $serializer->serialize($Company, 'json');
//
//                    $normalizer = new ObjectNormalizer();
//                    $normalizer->setCircularReferenceLimit(1);
//                    $normalizer->setCircularReferenceHandler(function ($Company) {
//                        return $Company->getId();
//                    });
//                    $normalizers = array($normalizer);
//                    $s = new Serializer($normalizers, $Company);



                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "data" => $company
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Company not created"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Company not created, params failed"
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
     * Finds and displays a company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id = null)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $Company = $em->getRepository("AppBundle:Company")->findOneBy(array(
            "id" => $id
        ));

//        $comments = $em->getRepository("AppBundle:User")->findBy(array(
//           "id" => $company->getUser()->getId()
//        ), array('id'=>'desc'));



        $Company=array(
            "id"=>$Company->getId(),
            "name"=>$Company->getName(),
            "title"=>$Company->getTitledescription(),
            "description"=> $Company->getDescription(),
            "phone"=> $Company->getPhone(),
            "address"=>$Company->getAddress(),
            "photoheader"=>$Company->getPhotoavatar(),
            "photoavatar"=> $Company->getPhotoheader(),
            "id_user"=>$Company->getUser()->getId()
        );

        if(count($Company) >= 1){
            $data = array(
                "status" => "success",
                "code"	 => 200,
                "data"	 => $Company
            );
        }else{
            $data = array(
                "status" => "error",
                "code"	 => 400,
                "msg"	 => "Dont exists this Company!!"
            );
        }

        return $helpers->json($data);
    }

    /**
     * Displays a form to edit an existing company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
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

                $Company_id = $id;

                $createdAt = new \Datetime('now');
                $updatedAt = new \Datetime('now');

                $user_id = ($identity->id != null) ? $identity->id : null;
                $name = (isset($params->name)) ? $params->name : null;
                $description = (isset($params->description)) ? $params->description : null;
                $address = (isset($params->address)) ? $params->address : null;
                $phone = (isset($params->phone)) ? $params->phone : null;
                $titledescription = (isset($params->titledescription)) ? $params->titledescription : null;
                $photoavatar = null;
                $photoheader = null;

                if ($name != null &&  $description != null &&  $user_id != null) {
                    $em = $this->getDoctrine()->getManager();

                    $Company = $em->getRepository("AppBundle:Company")->findOneBy(
                        array(
                            "id" => $Company_id
                        ));

                    if (isset($identity->id) && $identity->id == $Company->getUser()->getId()) {

                        $Company->setName($name);
                        $Company->setTitledescription($titledescription);
                        $Company->setDescription($description);
                        $Company->setPhone($phone);
                        $Company->setAddress($address);
                        $Company->setPhotoavatar($photoavatar);
                        $Company->setPhotoheader($photoheader);

                        $em->persist($Company);
                        $em->flush();

                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Company updated success!!"
                        );
                    } else {
                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Company updated error, you not owner!!"
                        );
                    }
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Company updated error"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Company not updated, params failed"
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
     * Deletes a company entity.
     *
     * @Route("/{id}/delete", name="company_delete")
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

            $Company = $em->getRepository("AppBundle:Company")->findOneBy(array(
                "id" => $id
            ));

            if (is_object($Company) && $user_id != null) {
                if (isset($identity->id) &&
                    ($identity->id == $Company->getUser()->getId())) {

                    $em->remove($Company);
                    $em->flush();

                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "msg" => "Company deleted success"
                    );
                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Company not deleted"
                    );
                }
            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Company not deleted"
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


}
