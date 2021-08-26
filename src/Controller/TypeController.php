<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use function PHPUnit\Framework\throwException;

class TypeController extends AbstractController
{
    /**
     * @Route("/type", name="type_list")
     */
    public function listType() 
    {
        $types = $this->getDoctrine()->getRepository(Type::class)->findAll();
        return $this->render('type/index.html.twig', ['types' => $types,]);
    }

    /**
     * @Route("/type/detail/{id}", name="type_detail")
     */
    public function detailType($id) 
    {
        $type = $this->getDoctrine()->getRepository(Type::class)->find($id);
        return $this->render('type/detail.html.twig', ['type' => $type,]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/type/delete/{id}", name="type_delete")
     */
    public function deleteType($id) {
        $type = $this->getDoctrine()->getRepository(Type::class)->find($id);
        if ($type == null) {
            $this->addFlash("Error","Invalid book ID");
            return $this->redirectToRoute("type_list");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($type);
        $manager->flush();
        $this->addFlash("Success","Delete book succeed !");
        return $this->redirectToRoute('type_list');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/type/create", name="type_create")
     */
    public function createType(Request $request) {
        $type = new Type();
        $form = $this->createForm(TypeType::class,$type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $type->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('type_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $type->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($type);
            $manager->flush();
            $this->addFlash("Success","Create type successfully !");
            return $this->redirectToRoute("type_list");
        }
        return $this->render("type/create.html.twig",["form" => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/type/update/{id}", name="type_update")
     */
    public function updateType(Request $request, $id) {
        $type = $this->getDoctrine()->getRepository(Type::class)->find($id);
        $form = $this->createForm(TypeType::class,$type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $type->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('type_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $type->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($type);
            $manager->flush();
            $this->addFlash("Success","Update book successfully !");
            return $this->redirectToRoute("type_list");
        }
        return $this->render("type/update.html.twig",["form" => $form->createView()]);
    }
}
