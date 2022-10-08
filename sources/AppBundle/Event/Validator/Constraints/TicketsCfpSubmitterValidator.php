<?php

namespace AppBundle\Event\Validator\Constraints;

use AppBundle\Event\Model\Ticket;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TicketsCfpSubmitterValidator extends ConstraintValidator
{
    /**
     * @param Ticket[] $value
     * @param PersonalMember $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        $specialCFPSubmitter = 0;

        foreach ($value as $index => $ticket) {
            if ($ticket->getTicketEventType() &&
                $ticket->getTicketEventType()->getTicketType() &&
                $ticket->getTicketEventType()->getTicketType()->getIsRestrictedToCfpSubmitter()) {
                $specialCFPSubmitter++;

                // On autorise qu'un seul ticket au tarif CFP submitter
                if ($specialCFPSubmitter > 1) {
                    $this->context->buildViolation($constraint->messageTooMuchCfpSubmitterTickets)
                        ->setParameter('{{ ticket_pretty_name }}', $ticket->getTicketEventType()->getTicketType()->getPrettyName())
                        ->atPath($index)
                        ->addViolation()
                    ;
                }
            }
        }
    }
}
