<?php
/**
 * Created by PhpStorm.
 * User: dorin.penea
 * Date: 2/21/2018
 * Time: 11:11 AM
 */

namespace App\Controller;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends Controller
{
    /**
     * @Route("/books",  name="get-books",  methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns the all books",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Book::class)
     *     )
     * )
     * @SWG\Tag(name="books")
     */
    public function getBooksAction(Request $request)
    {
        $books = $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Book::class)
            ->findAll();

        $serializer = $this->container->get('serializer');
        $serializedBooks = [];

        foreach ($books as $book) {
            $serializedBooks[] = $serializer->decode(
                $serializer->serialize($book, 'json'),
                'json'
            );
        }
        return new JsonResponse(
            $serializedBooks
        );
    }

    /**
     * @Route("/books/{id}",  name="get-book",  methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns new created book",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Book::class)
     *     )
     * )
     * @SWG\Tag(name="books")
     */
    public function getBookAction(Request $request)
    {
        $book = $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Book::class)
            ->findOneBy(
                [
                    'id' => $request->query->get('id')
                ]
            );

        $serializer = $this->container->get('serializer');
        $serializedBook = $serializer->decode(
            $serializer->serialize($book, 'json'),
            'json'
        );

        return new JsonResponse(
            $serializedBook
        );
    }

    /**
     * @Route("/books",  name="create-book",  methods={"POST"})
     * @param Request $request
     *
     * @SWG\Parameter(
     *     name="book_definition",
     *     in="body",
     *     type="object",
     *     description="The book description",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Book::class)
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns new created book",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Book::class)
     *     )
     * )
     * @SWG\Tag(name="books")
     */
    public function createBookAction(Request $request)
    {

        $sBook = $request->getContent();
        $sBook = (json_decode($sBook)[0]);
//        var_export(json_encode(json_decode($sBook)));
        $book = new Book();
        $book->setDescription($sBook->description);
        $book->setAuthor($sBook->author);
        $book->setTitle($sBook->title);
        $book->setIsbn($sBook->isbn);

        $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->persist($book);
        $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->flush();
        $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->clear();
        return $request->getContent();
    }
}