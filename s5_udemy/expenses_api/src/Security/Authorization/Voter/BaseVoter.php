<?php
//src/Security/Authorization/Voter/BaseVoter.php
declare(strict_types=1);

namespace App\Security\Authorization\Voter;

use App\Repository\GroupRepository;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

abstract class BaseVoter extends Voter
{
    protected Security $security;
    protected GroupRepository $groupRepository;

    public function __construct(Security $security, GroupRepository $groupRepository)
    {
        $this->security = $security;
        $this->groupRepository = $groupRepository;
    }
}
