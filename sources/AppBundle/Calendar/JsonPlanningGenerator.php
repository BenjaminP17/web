<?php

namespace AppBundle\Calendar;

use AppBundle\CFP\PhotoStorage;
use AppBundle\Event\Model\Event;
use AppBundle\Event\Model\Planning;
use AppBundle\Event\Model\Repository\TalkRepository;
use AppBundle\Event\Model\Room;
use AppBundle\Event\Model\Speaker;
use AppBundle\Event\Model\Talk;

class JsonPlanningGenerator
{
    /**
     * @var TalkRepository
     */
    private $talkRepository;

    /**
     * @var PhotoStorage
     */
    private $photoStorage;

    /**
     * @param TalkRepository $talkRepository
     * @param PhotoStorage $photoStorage
     */
    public function __construct(TalkRepository $talkRepository, PhotoStorage $photoStorage)
    {
        $this->talkRepository = $talkRepository;
        $this->photoStorage = $photoStorage;
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function generate(Event $event)
    {
        $talks = $this->talkRepository->getByEventWithSpeakers($event);

        $data = [];

        foreach ($talks as $talkWithData) {
            /**
             * @var $talk Talk
             */
            $talk = $talkWithData['talk'];

            /**
             * @var $planning Planning
             */
            $planning = $talkWithData['planning'];

            /**
             * @var $room Room
             */
            $room = $talkWithData['room'];

            /**
             * @var $speakers Speaker[]
             */
            $speakers = $talkWithData['.aggregation']['speaker'];

            $conferenciers = [];
            foreach ($speakers as $speaker) {
                $conferenciers[] = [
                    'img' => 'https://afup.org' . $this->photoStorage->getUrl($speaker),
                    'link' => sprintf('https://event.afup.org/%s/speakers/#%d', $event->getPath(), $speaker->getId()),
                    'name' => $speaker->getLabel(),
                ];
            }

            $timeZone = new \DateTimeZone("Europe/Paris");
            $start = $planning->getStart()->setTimezone($timeZone);
            $end = $planning->getEnd()->setTimezone($timeZone);

            $data[] = [
                'conferenciers' => $conferenciers,
                'date' => $start->format('d/m/Y H:i') . '-' . $end->format('H:i'),
                'date_start' => $start->format(\Datetime::ISO8601),
                'date_end' => $end->format(\Datetime::ISO8601),
                'detail' => strip_tags(html_entity_decode($talk->getAbstract())),
                'horaire' => $start->format('H:i') . '-' . $end->format('H:i'),
                'id' => $talk->getId(),
                'lang' => $talk->getLanguageLabel(),
                'name' => $talk->getTitle(),
                'salle' => $room->getName(),
            ];
        }

        return $data;
    }
}
