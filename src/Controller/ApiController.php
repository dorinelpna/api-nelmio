<?php
/**
 * Created by PhpStorm.
 * User: dorin.penea
 * Date: 2/21/2018
 * Time: 11:11 AM
 */

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api", name="api-controller")
 */
class ApiController
{
    /**
     * @Route("/get-books",  name="get-books",  methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the all books"
     * )
     */
    public function getBooksAction(Request $request)
    {
        return new JsonResponse(
            [
                ['name' => '1000 leghe sub mari'],
                ['name' => 'Basme populare']
            ]
        );
    }
}