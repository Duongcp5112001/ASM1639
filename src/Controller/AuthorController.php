<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use function PHPUnit\Framework\throwException;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author/api/viewallauthor", methods={"GET"}, name="view_all_author_api")
     */
    public function viewAllAuthorAPI (SerializerInterface $serializer){
        $authors = $this->getDoctrine()->getRepository(Author::class)->findAll();
        $data = $serializer->serialize($authors,'json');
        return new Response($data,Response::HTTP_OK,["content_type"=>"application/json"]);
    }

    /**
     * @Route("/author/api/viewbyid/{id}", methods={"GET"}, name="view_author_by_id_api")
     */
    public function viewAuthorByIdAPI(SerializerInterface $serializer, $id){
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        if ($author==null){
            return new Response("Author not exist",Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($author,'xml');
        return new Response($data,200,["content-type"=>"application/xml"]);
    }

    /**
     * @Route("/author/api/delete/{id}", methods={"DELETE"}, name="delete_author_api")
     */
    public function deleteAuthorAPI($id){
        try 
        {
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
            if($author==null){
                return new Response("Author ID is invalid",Response::HTTP_BAD_REQUEST);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($author);
            $manager->flush();
            return new Response("Author has been deleted",Response::HTTP_FOUND);
        }
        catch (\Exception $e)
        {
            return new Response(json_encode(["Error" => $e->getMessage()]),Response::HTTP_BAD_REQUEST,["content-type"=>"application/json"]);
        }
    }
    
    /**
     * @Route("/author/api/create", methods={"POST"}, name="create_author_api")
     */
    public function createAuthorAPI(Request $request){
        try
        {
            $author = new Author();
            $data = json_decode($request->getContent(), true);
            $author->setName($data['name']);
            $author->setBirthplace($data['birthplace']);
            $author->setBirthday(\DateTime::createFromFormat('Y-m-d',$data['birthday']));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($author);
            $manager->flush();
            return new Response("Author has been created",Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new Response(json_encode(["Error" => $e->getMessage()]),Response::HTTP_BAD_REQUEST,["content-type"=>"application/json"]);
        }
    }

    /**
     * @Route("/author/api/update/{id}", methods={"PUT"}, name="update_author_api")
     */
    public function updateAuthorAPI(Request $request,$id){
        try
        {
            $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
            $data = json_decode($request->getContent(), true);
            $author->setName($data['name']);
            $author->setBirthplace($data['birthplace']);
            $author->setBirthday(\DateTime::createFromFormat('Y-m-d',$data['birthday']));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($author);
            $manager->flush();
            return new Response("Author has been updated",Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
            return new Response(json_encode(["Error" => $e->getMessage()]),Response::HTTP_BAD_REQUEST,["content-type"=>"application/json"]);
        }
    }

    /**
     * @Route("/author", name="author_list")
     */
    public function listAuthor(){
        $authors = $this->getDoctrine()->getRepository(Author::class)->findAll();
        return $this->render("author/index.html.twig",['authors'=>$authors]);
    }

    /**
     * @Route("/author/detail/{id}", name="author_detail")
     */
    public function detailAuthor ($id){
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        if ($author == null){
            $this -> addFlash("Error","Author ID is invalid");
            return $this -> redirectToRoute("author_list");
        }
        return $this->render("author/detail.html.twig",['author'=>$author]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/author/delete/{id}", name="author_delete")
     */
    public function deleteAuthor ($id){
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        if ($author == null) {
            $this -> addFlash("Error","Author ID is invalid");
            return $this->redirectToRoute("author_list");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($author);
        $manager->flush();
        $this -> addFlash("Success","Author has been deteled");
        return $this->redirectToRoute("author_list");
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/author/create", name="author_create")
     */
    public function createAuthor (Request $request){
        $author = new Author();
        $form = $this->createForm(AuthorType::class,$author) ;
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $author->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('author_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $author->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($author);
            $manager->flush();
            $this->addFlash("Success","Add author successfully");
            return $this->redirectToRoute("author_list");
        }
        return $this->render("author/create.html.twig",["form"=>$form->createView()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/author/update/{id}", name="author_update")
     */
    public function updateAuthor (Request $request,$id){
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class,$author) ;
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $author->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('author_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $author->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($author);
            $manager->flush();
            $this->addFlash("Success","Update author successfully");
            return $this->redirectToRoute("author_list");
        }
        return $this->render("author/update.html.twig",["form"=>$form->createView()]);
    }
}