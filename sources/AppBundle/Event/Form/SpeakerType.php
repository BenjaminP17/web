<?php


namespace AppBundle\Event\Form;

use AppBundle\Event\Model\GithubUser;
use AppBundle\Event\Model\Repository\GithubUserRepository;
use AppBundle\Event\Model\Repository\SpeakerRepository;
use AppBundle\Event\Model\Speaker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SpeakerType extends AbstractType
{
    const OPT_PHOTO_REQUIRED = 'photo_required';
    const OPT_USER_GITHUB = 'user_github';

    /** @var GithubUserRepository */
    private $githubUserRepository;
    /** @var SpeakerRepository */
    private $speakerRepository;
    /** @var TokenStorage */
    private $tokenStorage;

    public function __construct(
        GithubUserRepository $githubUserRepository,
        SpeakerRepository $speakerRepository,
        TokenStorage $tokenStorage
    ) {
        $this->githubUserRepository = $githubUserRepository;
        $this->speakerRepository = $speakerRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class, ['choices' => ['M' => 'M', 'Mme' => 'Mme']])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('company', TextType::class, ['required' => false])
            ->add('locality', TextType::class, ['required' => false, 'help' => 'Indiquer votre ville nous permet de mieux anticiper les déplacements'])
            ->add('phone_number', TextType::class, ['required' => false, 'property_path' => 'phoneNumber', 'label' => 'Phone', 'help' => 'Utilisé uniquement pour vous contacter avant et durant de l\'événement.'])
            ->add('biography', TextareaType::class)
            ->add('twitter', TextType::class, ['required' => false])
            ->add('referent_person', TextType::class, ['required' => false, 'property_path' => 'referentPerson',])

        ;
        if (true === $options[self::OPT_USER_GITHUB]) {
            $builder
                ->add('github_user',
                    ChoiceType::class,
                    [
                        'property_path' => 'githubUser',
                        'label' => 'Utilisateur GitHub',
                        'required' => false,
                        'choice_label' => function (GithubUser $user) {
                            return $user->getLabel();
                        },
                        'choice_value' => function ($choice) {
                            if ($choice instanceof GithubUser) {
                                return $choice->getId();
                            }
                            return $choice;
                        },
                        'choice_loader' => new CallbackChoiceLoader(function () {
                            return $this->githubUserRepository->getAllOrderedByLogin();
                        }),
                    ]
                )
            ;
        }

        $builder
            ->add('photoFile', FileType::class, ['label' => 'Photo de profil', 'data_class' => null, 'required' => $options[self::OPT_PHOTO_REQUIRED]])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {
            $speaker = $formEvent->getData();
            if ($speaker instanceof Speaker && $speaker->getId() === null && $this->tokenStorage->getToken()->getUser() !== null) {
                $previousSpeakerInfos = $this->speakerRepository->getFromLastEventAndUserId($speaker->getEventId(), $this->tokenStorage->getToken()->getUser()->getId());
                if ($previousSpeakerInfos instanceof Speaker) {
                    $speaker->setCivility($previousSpeakerInfos->getCivility());
                    $speaker->setFirstname($previousSpeakerInfos->getFirstname());
                    $speaker->setLastname($previousSpeakerInfos->getLastname());
                    $speaker->setEmail($previousSpeakerInfos->getEmail());
                    $speaker->setCompany($previousSpeakerInfos->getCompany());
                    $speaker->setLocality($previousSpeakerInfos->getLocality());
                    $speaker->setPhoneNumber($previousSpeakerInfos->getPhoneNumber());
                    $speaker->setBiography($previousSpeakerInfos->getBiography());
                    $speaker->setTwitter($previousSpeakerInfos->getTwitter());
                    $speaker->setPhoto($previousSpeakerInfos->getPhoto());

                    $formEvent->getForm()->add('isFromPreviousEvent', HiddenType::class, ['mapped' => false]);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                self::OPT_PHOTO_REQUIRED => true,
                self::OPT_USER_GITHUB => false,
            ])
        ;
    }
}
