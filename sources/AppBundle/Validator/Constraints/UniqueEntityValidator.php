<?php

namespace AppBundle\Validator\Constraints;

use CCMBenchmark\TingBundle\Repository\RepositoryFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEntityValidator extends ConstraintValidator
{
    private $repositoryFactory;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->repositoryFactory = $repositoryFactory;
    }

    /**
     * @inheritDoc
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEntity) {
            throw new UnexpectedTypeException($constraint, UniqueEntity::class);
        }
        $repository = $this->repositoryFactory->get($constraint->repository);

        $fields = (array) $constraint->fields;
        $criteria = [];
        foreach ($fields as $field) {
            $propertyName    = 'get' . $field;
            $criteria[$field] = $entity->$propertyName();
        }

        $myEntity = $repository->getOneBy($criteria);

        if ($myEntity !== null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ data }}', implode(', ', $criteria))
                ->addViolation();
        }
    }
}
