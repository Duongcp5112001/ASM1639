<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
   /**
     * @Route("/orderuser", name="order_list_user")
     */
    public function listOrder () {
        $orders = $this->getDoctrine()
                              ->getRepository(Order::class)
                              ->findAll();
    
        return $this->render(
                        "order/index.html.twig",
                        [
                            'orders' => $orders
                        ]
                     );
    }

    /**
     * @Route("/orderadmin", name="order_list_admin")
     */
    public function listOrderadmin () {
        $orders = $this->getDoctrine()
                              ->getRepository(Order::class)
                              ->findAll();
    
        return $this->render(
                        "order/index1.html.twig",
                        [
                            'orders' => $orders
                        ]
                     );
    }

    /**
     * @Route("/order/detailuser/{id}", name="order_detail_user")
     */
    public function detailOrder ($id) {
        $order = $this->getDoctrine()
                       ->getRepository(Order::class)
                       ->find($id);

        if ($order == null) {
            $this->addFlash("Error", "Order ID in invalid");
            return $this->redirectToRoute("order_list");
        }

        return $this->render(
                        "order/detail.html.twig",
                        [
                          'order' => $order
                        ]
        );
    }

    /**
     * @Route("/order/detailadmin/{id}", name="order_detail_admin")
     */
    public function detailOrderAdmin ($id) {
        $order = $this->getDoctrine()
                       ->getRepository(Order::class)
                       ->find($id);

        if ($order == null) {
            $this->addFlash("Error", "Order ID in invalid");
            return $this->redirectToRoute("order_list_admin");
        }

        return $this->render(
                        "order/detail1.html.twig",
                        [
                          'order' => $order
                        ]
        );
    }

    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/order/deleteuser/{id}", name="order_delete_user")
     */
    public function deleteOrder ($id)
    {
        $order = $this->getDoctrine()
                          ->getRepository(Order::class)
                          ->find($id);
        if($order == null) {
            $this->addFlash("Error", "Invalid Order ID");
            return $this->redirectToRoute("order_list_user");
        }
        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->remove($order);
        $manager->flush();
        $this->addFlash("Info", "Order has been deleted");    
        return $this->redirectToRoute("order_list_user");
    }

    /**
     * @Route("/order/deleteuser/{id}", name="order_delete_admin")
     */
    public function deleteOrderAdmin ($id)
    {
        $order = $this->getDoctrine()
                          ->getRepository(Order::class)
                          ->find($id);
        if($order == null) {
            $this->addFlash("Error", "Invalid Order ID");
            return $this->redirectToRoute("order_list_admin");
        }
        $manager = $this->getDoctrine()
                        ->getManager();
        $manager->remove($order);
        $manager->flush();
        $this->addFlash("Info", "Order has been deleted");    
        return $this->redirectToRoute("order_list_admin");
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/order/createuser", name="order_create_user")
     */
    public function createOrder (Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($order);
            $manager->flush();

            $this->addFlash("Info", "Order has been added!");
            return $this->redirectToRoute("order_list_user");
        }
        return $this->render(
            "order/create.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }

    /**
     * @Route("/order/createadmin/", name="order_create_admin")
     */
    public function createOrderAdmin (Request $request)
    {

        $order = new Order();
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager = $this->getDoctrine()
                            ->getManager();
            $manager->persist($order);
            $manager->flush();

            $this->addFlash("Info", "Order has been added!");
            return $this->redirectToRoute("order_list_admin");
        }
        return $this->render(
            "order/create1.html.twig",
            [
                "form" => $form->createView()
            ]
        );
    }
}
