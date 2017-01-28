<?php
namespace AppBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RootScaleConverter implements ParamConverterInterface
{

    protected $em;

    /**
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct( \Doctrine\ORM\EntityManager $em )
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     *
     * Check, if object supported by our converter
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * Applies converting
     *
     * @throws \InvalidArgumentException When route attributes are missing
     * @throws NotFoundHttpException     When object not found
     */
    public function apply(Request $request, ParamConverter $configuration)
    {

        $addRoot = $request->attributes->get('addRoot');

        $request->attributes->set($configuration->getName(), $addRoot);
    }
}