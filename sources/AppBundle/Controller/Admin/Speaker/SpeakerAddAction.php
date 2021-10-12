<?php

namespace AppBundle\Controller\Admin\Speaker;

use Afup\Site\Logger\DbLoggerTrait;
use AppBundle\CFP\PhotoStorage;
use AppBundle\Event\Form\SpeakerFormData;
use AppBundle\Event\Form\SpeakerType;
use AppBundle\Event\Model\Event;
use AppBundle\Event\Model\Repository\EventRepository;
use AppBundle\Event\Model\Repository\SpeakerRepository;
use AppBundle\Event\Model\Speaker;
use Assert\Assertion;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class SpeakerAddAction
{
    use DbLoggerTrait;

    /** @var EventRepository */
    private $eventRepository;
    /** @var SpeakerRepository */
    private $speakerRepository;
    /** @var FormFactoryInterface */
    private $formFactory;
    /** @var FlashBagInterface */
    private $flashBag;
    /** @var UrlGeneratorInterface */
    private $urlGenerator;
    /** @var PhotoStorage */
    private $photoStorage;
    /** @var Environment */
    private $twig;

    public function __construct(
        EventRepository $eventRepository,
        SpeakerRepository $speakerRepository,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag,
        UrlGeneratorInterface $urlGenerator,
        PhotoStorage $photoStorage,
        Environment $twig
    ) {
        $this->eventRepository = $eventRepository;
        $this->speakerRepository = $speakerRepository;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
        $this->urlGenerator = $urlGenerator;
        $this->photoStorage = $photoStorage;
        $this->twig = $twig;
    }

    public function __invoke(Request $request)
    {
        /** @var Event $event */
        $event = $this->eventRepository->get($request->query->get('eventId'));
        Assertion::notNull($event);
        $data = new SpeakerFormData();
        $form = $this->formFactory->create(SpeakerType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $speaker = new Speaker();
            $speaker->setEventId($event->getId());
            $speaker->setCivility($data->civility);
            $speaker->setFirstname($data->firstname);
            $speaker->setLastname($data->lastname);
            $speaker->setBiography($data->biography);
            $speaker->setTwitter($data->twitter);
            $speaker->setEmail($data->email);
            if ($data->user !== null) {
                $speaker->setUser($data->user->getId());
            }
            $speaker->setCompany($data->company);
            $this->speakerRepository->save($speaker);
            if (null !== $data->photo) {
                $fileName = $this->photoStorage->store($data->photo, $speaker);
                $speaker->setPhoto($fileName);
                $this->speakerRepository->save($speaker);
            }
            $this->log('Ajout du conférencier de ' . $speaker->getFirstname() . ' ' . $speaker->getLastname());
            $this->flashBag->add('notice', 'Le conférencier a été ajouté');

            return new RedirectResponse($this->urlGenerator->generate('admin_speaker_list', ['eventId' => $event->getId()]));
        }

        return new Response($this->twig->render('admin/speaker/add.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]));
    }
}
