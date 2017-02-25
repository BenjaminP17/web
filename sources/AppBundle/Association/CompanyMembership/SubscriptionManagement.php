<?php


namespace AppBundle\Association\CompanyMembership;

use Afup\Site\Association\Cotisations;
use AppBundle\Association\Model\CompanyMember;
use AppBundle\LegacyModelFactory;

class SubscriptionManagement
{
    /**
     * @var LegacyModelFactory
     */
    private $legacyModelFactory;

    public function __construct(LegacyModelFactory $legacyModelFactory)
    {
        $this->legacyModelFactory = $legacyModelFactory;
    }

    public function createInvoiceForInscription(CompanyMember $company, $numberOfMembers)
    {
        /**
         * @var $subscription Cotisations
         */
        $subscription = $this->legacyModelFactory->createObject(Cotisations::class);

        $endSubscription = $subscription->finProchaineCotisation(false);

        // Create the invoice
        $subscription->ajouter(
            AFUP_PERSONNES_MORALES,
            $company->getId(),
            ceil($numberOfMembers / AFUP_PERSONNE_MORALE_SEUIL) * AFUP_COTISATION_PERSONNE_MORALE,
            null,
            null,
            (new \DateTime())->format('U'),
            $endSubscription->format('U'),
            ''
        );
        $subscriptionArray = $subscription->obtenirDerniere(AFUP_PERSONNES_MORALES, $company->getId());

        if ($subscriptionArray === false) {
            throw new \RuntimeException('An error occured');
        }

        return ['invoice' => $subscriptionArray['numero_facture'], 'token' => $subscriptionArray['token']];
    }
}
