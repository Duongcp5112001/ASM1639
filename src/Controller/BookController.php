<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use function PHPUnit\Framework\throwException;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_list")
     */
    public function listBook() 
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
        return $this->render('book/index.html.twig', ['books' => $books,]);
    }

    /**
     * @Route("/bookuser", name="book_list_user")
     */
    public function listBookUser() 
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
        return $this->render('user/index.html.twig', ['books' => $books,]);
    }

    /**
     * @Route("/book/detail/{id}", name="book_detail")
     */
    public function detailBook($id) 
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        return $this->render('book/detail.html.twig', ['book' => $book,]);
    }

    /**
     * @Route("/bookuser/detail/{id}", name="book_detail_user")
     */
    public function detailBookUser($id) 
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        return $this->render('user/detail.html.twig', ['book' => $book,]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/book/delete/{id}", name="book_delete")
     */
    public function deleteBook($id) {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        if ($book == null) {
            $this->addFlash("Error","Invalid book ID");
            return $this->redirectToRoute("book_list");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($book);
        $manager->flush();
        $this->addFlash("Success","Delete book succeed !");
        return $this->redirectToRoute('book_list');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/book/create", name="book_create")
     */
    public function createBook(Request $request) {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $book->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('book_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $book->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($book);
            $manager->flush();
            $this->addFlash("Success","Create book successfully !");
            return $this->redirectToRoute("book_list");
        }
        return $this->render("book/create.html.twig",["form" => $form->createView()]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/book/update/{id}", name="book_update")
     */
    public function updateBook(Request $request, $id) {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $book->getImage();
            $fileName = md5(uniqid());
            $fileExtension = $image->guessExtension();
            $imageName = $fileName . '.' . $fileExtension;
            try {
                $image->move($this->getParameter('book_image'), $imageName);
            } catch (FileException $e) {
                throwException($e);
            }
            $book->setImage($imageName);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($book);
            $manager->flush();
            $this->addFlash("Success","Update book successfully !");
            return $this->redirectToRoute("book_list");
        }
        return $this->render("book/update.html.twig",["form" => $form->createView()]);
    }
}
