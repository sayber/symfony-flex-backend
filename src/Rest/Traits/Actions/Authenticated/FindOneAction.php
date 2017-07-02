<?php
declare(strict_types=1);
/**
 * /src/Rest/Traits/Actions/Authenticated/FindOneAction.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Rest\Traits\Actions\Authenticated;

use App\Rest\Traits\Methods\FindOneMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait FindOneAction
 *
 * Trait to add 'findOneAction' for REST controllers for authenticated users.
 *
 * @see \App\Rest\Traits\Methods\FindOneMethod for detailed documents.
 *
 * @package App\Rest\Traits\Actions\Authenticated
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait FindOneAction
{
    // Traits
    use FindOneMethod;

    /**
     * @Route(
     *      "/{id}",
     *      requirements={
     *          "id" = "^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$"
     *      }
     *  )
     *
     * @Method({"GET"})
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function findOneAction(Request $request, string $id): Response
    {
        return $this->findOneMethod($request, $id);
    }
}