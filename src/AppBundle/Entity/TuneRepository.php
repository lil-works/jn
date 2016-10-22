<?php

namespace AppBundle\Entity;

/**
 * TuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TuneRepository extends \Doctrine\ORM\EntityRepository
{
    public function findVersions($tune,$realbook){
        $em = $this->getEntityManager();
        $dql = "
            SELECT r.id,r.indexName,r.firstPage+tr.page-1 as page  FROM AppBundle:Tune t
            LEFT JOIN AppBundle:TunesRealbooks tr WITH tr.tune = t.id
            LEFT JOIN AppBundle:Realbook r WITH tr.realbook = r.id
            WHERE tr.tune = :tune AND r.id != :realbook

            GROUP BY r.id

              ";
        $query = $em->createQuery($dql);
        $query->setParameter('tune', $tune->getId());
        $query->setParameter('realbook', $realbook->getId());

        return $query->getResult();
    }
}
