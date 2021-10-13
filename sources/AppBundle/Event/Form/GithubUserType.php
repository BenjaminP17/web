<?php


namespace AppBundle\Event\Form;

use AppBundle\Event\Model\GithubUser;
use AppBundle\Github\Exception\UnableToFindGithubUserException;
use AppBundle\Github\Exception\UnableToGetGithubUserInfosException;
use AppBundle\Github\GithubClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GithubUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, [
                'invalid_message' => 'Impossible de charger les informations de l\'utilisateur GitHub.'
            ])
            ->add('afupCrew', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
        ;

        /** @var GithubClient $githubClient */
        $githubClient = $options['github_client'];

        $builder->get('user')
            ->addModelTransformer(new CallbackTransformer(
                /**
                 * @param $githubUser null|GithubUser
                 */
                function ($githubUser) {
                    return $githubUser === null ? null : $githubUser->getLogin();
                },
                /**
                 * @param $githubUsername string
                 */
                function ($githubUsername) use ($githubClient) {
                    if ($githubUsername === null) {
                        return null;
                    }

                    try {
                        return $githubClient->getUserInfos($githubUsername);
                    } catch (UnableToFindGithubUserException $e) {
                        throw new TransformationFailedException($e->getMessage());
                    } catch (UnableToGetGithubUserInfosException $e) {
                        throw new TransformationFailedException($e->getMessage());
                    }
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GithubUserFormData::class,
            'github_client' => null,
        ]);

        $resolver->setAllowedTypes('github_client', GithubClient::class);
    }
}
