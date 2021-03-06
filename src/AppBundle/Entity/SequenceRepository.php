<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Query\ResultSetMapping;
/**
 * SequenceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SequenceRepository extends \Doctrine\ORM\EntityRepository
{

    public function findChanges($sequence){
        $sql = "
               SELECT
                    s.id as sequenceId,
                    w.id as westernSystemId,
                    d.value + i.delta as changeRootDigitValue,

                    c.id as changeId,
                    i.roman as rootRoman,
                    w2.name as rootName,
                    sca.name as scaleName,
                    ( SELECT name from western_system WHERE
                    root = (SELECT id FROM western_system WHERE value = MOD( d.value + i.delta , 12)) AND
                     digit = (SELECT id FROM digit WHERE value = MOD( i2.delta + i.delta + d.value , 12)) ) as digit

                    FROM sequence s
                    LEFT JOIN western_system w on w.id = s.root
                    LEFT JOIN digit d on d.id = w.digit
                    LEFT JOIN sequences_changes sc on sc.sequence_id = s.id
                    LEFT JOIN sequence_changes c on c.id = sc.sequence_changes_id
                    LEFT JOIN intervale i on i.id = c.intervale
                    LEFT JOIN western_system w2 on w2.root=w.id AND w2.intervale = i.id
                    LEFT JOIN scale sca on sca.id = c.scale
                    LEFT JOIN scales_intervales si on si.scale_id = sca.id
                    LEFT JOIN intervale i2 on i2.id = si.intervale_id


                    WHERE s.id = :sequenceId
            ";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('sequenceId', 'sequenceId');
        $rsm->addScalarResult('westernSystemId', 'westernSystemId');
        $rsm->addScalarResult('changeRootDigitValue', 'changeRootDigitValue');
        $rsm->addScalarResult('changeId', 'changeId');
        $rsm->addScalarResult('rootRoman', 'rootRoman');
        $rsm->addScalarResult('rootName', 'rootName');
        $rsm->addScalarResult('scaleName', 'scaleName');
        $rsm->addScalarResult('digit', 'digit');

        //$rsm->addScalarResult('sequenceId', 'sequenceId');
        /*$rsm->addEntityResult('AppBundle\Entity\WesternSystem' , 'westernSystem');
        $rsm->addFieldResult('westernSystem','westernSystemId','id');
        $rsm->addEntityResult('AppBundle\Entity\SequenceChanges' , 'change');
        $rsm->addFieldResult('change','changeId','id');
        $rsm->addFieldResult('change','changeBar','bar');
        $rsm->addFieldResult('change','changeBeat','beat');

        $rsm->addEntityResult('AppBundle\Entity\Intervale' , 'intervale');
        $rsm->addFieldResult('intervale','intervaleId','id');

        $rsm->addEntityResult('AppBundle\Entity\WesternSystem' , 'w');
        $rsm->addFieldResult('w','id','id');

        $rsm->addJoinedEntityResult('AppBundle\Entity\Intervale' , 'i' , 's' , 'intervale');
        $rsm->addFieldResult('i','id','id');
        $rsm->addJoinedEntityResult('AppBundle\Entity\Intervale' , 'i2' , 's' , 'intervale');
        $rsm->addFieldResult('i','id','id');
*/

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("sequenceId",$sequence->getId());
        return $query->getScalarResult();
    }
}
