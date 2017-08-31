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



                   // $Company = $em->getRepository("AppBundle:Company")->findOneById($Company->getId());


                    $Company = $em->getRepository("AppBundle:Company")->findOneBy(
                        array(
                            "user" => $user,
                            "titledescription" => $titledescription,
                        ));

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
                        "data" => $Company
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
    public function showAction(Company $company)
    {
        $deleteForm = $this->createDeleteForm($company);

        return $this->render('company/show.html.twig', array(
            'company' => $company,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Company $company)
    {
        $deleteForm = $this->createDeleteForm($company);
        $editForm = $this->createForm('AppBundle\Form\CompanyType', $company);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_edit', array('id' => $company->getId()));
        }

        return $this->render('company/edit.html.twig', array(
            'company' => $company,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a company entity.
     *
     * @Route("/{id}", name="company_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Company $company)
    {
        $form = $this->createDeleteForm($company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }

    /**
     * Creates a form to delete a company entity.
     *
     * @param Company $company The company entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Company $company)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('company_delete', array('id' => $company->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
