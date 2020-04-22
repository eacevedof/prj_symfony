<?php
//src/Api/Action/Group/RemoveUser.php
declare(strict_types=1);

namespace App\Api\Action\Group;

use App\Api\Action\RequestTransformer;
use App\Entity\User;
use App\Service\Group\GroupService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RemoveUser
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/groups/remove_user", methods={"POST"})
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        $this->groupService->removeUserFromGroup(
            RequestTransformer::getRequiredField($request, 'group_id'),
            RequestTransformer::getRequiredField($request, 'user_id'),
            $user
        );

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }//__invoke

}//RemoveUser