<?php


namespace AppBundle\Model;


use CCMBenchmark\Ting\Entity\NotifyProperty;
use CCMBenchmark\Ting\Entity\NotifyPropertyInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GithubUser implements NotifyPropertyInterface, UserInterface, \Serializable, EquatableInterface
{
    use NotifyProperty;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $githubId;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $profileUrl;

    /**
     * @var string
     */
    private $avatarUrl;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return GithubUser
     */
    public function setId($id)
    {
        $this->propertyChanged('id', $this->id, $id);
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getGithubId()
    {
        return $this->githubId;
    }

    /**
     * @param int $githubId
     * @return GithubUser
     */
    public function setGithubId($githubId)
    {
        $this->propertyChanged('githubId', $this->githubId, $githubId);
        $this->githubId = $githubId;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return GithubUser
     */
    public function setLogin($login)
    {
        $this->propertyChanged('login', $this->login, $login);
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return GithubUser
     */
    public function setName($name)
    {
        $this->propertyChanged('name', $this->name, $name);
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return GithubUser
     */
    public function setCompany($company)
    {
        $this->propertyChanged('company', $this->company, $company);
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return $this->profileUrl;
    }

    /**
     * @param string $profileUrl
     * @return GithubUser
     */
    public function setProfileUrl($profileUrl)
    {
        $this->propertyChanged('profileUrl', $this->profileUrl, $profileUrl);
        $this->profileUrl = $profileUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @param string $avatarUrl
     * @return GithubUser
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->propertyChanged('avatarUrl', $this->avatarUrl, $avatarUrl);
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return ['ROLE_GITHUB'];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getLogin();
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }
    /***************
     * Serializable
     **************/


    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(['id' => $this->id]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $user = unserialize($serialized);
        $this->id = $user['id'];
    }

    /***************
     * EquatableInterface
     ***************/

    /**
     * @inheritDoc
     */
    public function isEqualTo(UserInterface $user)
    {
        /**
         * @var self $user
         */
        return ($user->getId() === $this->id);
    }


}
