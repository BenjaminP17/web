<?php

namespace AppBundle\Controller\Admin\Talk;

use AppBundle\Indexation\Talks\Runner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UpdateIndexationAction
{
    /** @var FlashBagInterface */
    private $flashBag;
    /** @var Runner */
    private $runner;

    public function __construct(
        Runner $runner,
        FlashBagInterface $flashBag
    ) {
        $this->runner = $runner;
        $this->flashBag = $flashBag;
    }

    public function __invoke(Request $request)
    {
        $this->runner->run();
        $this->flashBag->add('notice', 'Indexation effectuée');

        return new RedirectResponse($request->headers->get('referer', '/pages/administration/index.php?page=forum_sessions'));
    }
}
