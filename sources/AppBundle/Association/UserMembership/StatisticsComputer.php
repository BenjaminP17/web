<?php

namespace AppBundle\Association\UserMembership;

use Afup\Site\Association\Personnes_Morales;
use AppBundle\Association\Model\Repository\UserRepository;
use AppBundle\Association\Model\User;

class StatisticsComputer
{
    /** @var UserRepository */
    private $userRepository;
    /** @var Personnes_Morales */
    private $personnesMorales;

    public function __construct(UserRepository $userRepository, Personnes_Morales $personnesMorales)
    {
        $this->userRepository = $userRepository;
        $this->personnesMorales = $personnesMorales;
    }

    public function computeStatistics()
    {
        $statistics = new Statistics();
        /** @var $users User[] */
        $users = $this->userRepository->getActiveMembers(UserRepository::USER_TYPE_ALL);
        foreach ($users as $user) {
            $statistics->usersCount++;
            if ($user->isMemberForCompany()) {
                $statistics->usersCountWithCompanies++;

                if (isset($companies[$user->getCompanyId()]) === false) {
                    $companies[$user->getCompanyId()] = true;
                    $statistics->companiesCountWithLinkedUsers++;
                }
            } else {
                $statistics->usersCountWithoutCompanies++;
            }
        }
        $statistics->companiesCount = $this->personnesMorales->obtenirNombrePersonnesMorales('1');

        return $statistics;
    }
}
