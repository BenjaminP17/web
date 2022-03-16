<?php

namespace AppBundle\Slack;

use AppBundle\Association\Model\Repository\UserRepository;

class UsersChecker
{
    const SUBSCRIPTION_DELAY = '+15 days';
    /**
     * @var UsersClient
     */
    private $usersClient;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UsersClient $usersClient, UserRepository $userRepository)
    {
        $this->usersClient = $usersClient;
        $this->userRepository = $userRepository;
    }

    /**
     * Retourne la liste des utilisateurs devant être supprimé du slack membre
     * @return array
     * @throws \CCMBenchmark\Ting\Query\QueryException
     */
    public function checkUsersValidity()
    {
        $result = [];
        $cursor = '';
        $today = new \DateTimeImmutable();
        do {
            //Récupère une page d'utilisateur dans Slack
            $page = $this->usersClient->loadPage($cursor);
            foreach ($page['members'] as $user) {
                // Ne traite pas les utilisateurs sans adresse courriel ou supprimé
                if (!isset($user['profile']['email']) || $user['deleted'] === true || $user['is_admin'] === true) {
                    continue;
                }
                $email = $user['profile']['email'];
                $userInfo = [
                    'slack_username' => $user['name'],
                    'slack_realname' => $user['real_name'],
                    'slack_email' => $email,
                    'user_found' => false,
                    'afup_last_subscription' => null,
                    'afup_user_id' => null,
                ];
                //Vérification de l'utilisateur Slack dans la base du site
                try {
                    $userDb = $this->userRepository->loadUserByEmaiOrAlternateEmail($email);
                    $userInfo['afup_last_subscription']=$userDb->getLastSubscription();
                    $userInfo['afup_user_id'] = $userDb->getId();
                    $userInfo['user_found']=true;

                    //Issue 1133 : on n'ajoute que les utilisateurs dont la date de fin de souscription est dépassée de 15 jours.
                    //Ca revient à tester si la date d'aujourd'hui est supérieure à la date de fin de souscription + 15 jours
                    $dateAlarm = clone $userDb->getLastSubscription();
                    $dateAlarm = $dateAlarm->modify(self::SUBSCRIPTION_DELAY);

                    if ($dateAlarm < $today) {
                        //Utilisateur inactif ou sans souscription : a supprimer
                        $result[] = $userInfo;
                    }
                } catch (\Symfony\Component\Security\Core\Exception\UsernameNotFoundException $e) {
                    //User Not found ! A supprimer de slack !
                    $result[] = $userInfo;
                }
            }
            //Récupère le curseur pour la page suivante
            $cursor = $page['response_metadata']['next_cursor'];
        } while ($cursor !== '');
        return $result;
    }
}
