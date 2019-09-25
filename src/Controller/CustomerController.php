<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Customer;
use App\Form\CustomerType;

/**
 * Customer controller.
 * @Route("/api", name="api_")
 */
class CustomerController extends FOSRestController
{
    /**
     * Lists all Customers.
     * @Rest\Get("/customer")
     *
     * @return Response
     */
    public function getCustomerAction()
    {
        $repository = $this->getDoctrine()->getRepository(Customer::class);
        $customers = $repository->findall();
        return $this->handleView($this->view($customers));
    }

    /**
     * @Route("/customer/{id}", name="customer_by_id", methods={"GET"})
     */
    public function customer($id)
    {
        $repository = $this->getDoctrine()->getRepository(Customer::class);
        $customer = $repository->findById($id);

        if(!$customer){
          throw new NotFoundHttpException('The customer does not exist');      
        }
        
        return $this->handleView($this->view($customer));
    }

    /**
     * Create Customer.
     * @Rest\Post("/customer")
     *
     * @return Response
     */
    public function postCustomerAction(Request $request)
    {
        $customer = new Customer();
        $customer->setCreatedAt(new \DateTime());
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        
        $form->submit($data);
        
        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($customer);
          $em->flush();
          return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

      /**
       * @Route("/customer/{id}", name="put_customer", methods={"PUT"}, requirements={"id"="\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b"})
       *
       * @return Response
       */
      public function put(Request $request, $id)
      {
          $existingCustomer = $this->getDoctrine()->getRepository(Customer::class)->find($id);

          if(!$existingCustomer){
            throw new NotFoundHttpException('The customer does not exist');      
          }

          $existingCustomer->setUpdatedAt(new \DateTime());
          $form = $this->createForm(CustomerType::class, $existingCustomer);

          $form->submit($request->request->all());

          if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
          }

          return $this->handleView($this->view($form->getErrors()));
      }

      /**
       * @Route("/customer/{id}", name="patch_customer", methods={"PATCH"}, requirements={"id"="\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b"})
       * @param int $id
       *
       * @return Response
       */
      public function patch(Request $request, $id)
      {
          $data = json_decode($request->getContent(), true);
          $existingCustomer = $this->getDoctrine()->getRepository(Customer::class)->find($id);

          if(!$existingCustomer){
            throw new NotFoundHttpException('The customer does not exist');      
          }

          $existingCustomer->setUpdatedAt(new \DateTime());
          $form = $this->createForm(CustomerType::class, $existingCustomer);

          $form->submit($data, false);

          if (false === $form->isValid()) {
              return $this->handleView($this->view($form->getErrors()));
          }

          $this->getDoctrine()->getManager()->flush();

          return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
    }

    /**
       * @Route("/customer/{id}", name="delete_customer", methods={"DELETE"}, requirements={"id"="\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b"})
       * @param int $id
       *
       * @return Response
       */
    public function deleteAction(string $id)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id);

        if(!$customer){
          throw new NotFoundHttpException('The customer does not exist');      
        }

        $this->getDoctrine()->getManager()->remove($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->view(null, Response::HTTP_OK);
    }
}