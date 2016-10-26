<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Query\ResultSetMapping;
/**
 * FingeringRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FingeringRepository extends \Doctrine\ORM\EntityRepository
{


    public function findOneByFingers($arrayOfFinger){

        $xList =  $yList = array();
        foreach($arrayOfFinger as $finger){
            array_push($yList,$finger->getY());
            array_push($xList,$finger->getX());
        }

        $sql = "SELECT f.id as fId,group_concat(ff.x order by y) as xList,group_concat(ff.y order by y) as yList
        FROM fingering AS f
        LEFT JOIN fingering_finger ff ON ff.fingering = f.id
        group by f.id
        Having xList = :xList and yList = :yList
        LIMIT 1";
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('fId', 'fId');



        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("xList",implode(',',$xList));
        $query->setParameter("yList",implode(',',$yList));

        $result = $query->getScalarResult();
        if(count($result)>0){
            return true;
        }else{

        return false;
        }


    }
    public function findFingeringByRootAndScale($instrument,$scale,$westernScale=null){

        $sql = "



		 SELECT
    fId,
    (SELECT
            COUNT(*)
        FROM
            scales_intervales
        WHERE
            scale_id = sId),
    IF((SELECT
                COUNT(id)
            FROM
                fingering_finger
            WHERE
                fingering = fId) = COUNT(x),
        IF((SELECT
                    COUNT(*)
                FROM
                    scales_intervales
                WHERE
                    scale_id = sId) > COUNT(x),
            NULL,
            IF(COUNT(DISTINCT (currentDigit)) = (SELECT
                        COUNT(*)
                    FROM
                        scales_intervales
                    WHERE
                        scale_id = sId),
                'ok',
                NULL)),
        NULL) AS ok,
    GROUP_CONCAT(x ORDER BY y) as xList,
    GROUP_CONCAT(y ORDER BY y) as yList,
    GROUP_CONCAT(currentDigitA order by y) as digitAList,
    GROUP_CONCAT(currentIntervale order by y) as intervaleList,
    GROUP_CONCAT(wsName order by y) as wsNameList

FROM
    (SELECT
        instrument.id AS instrumentId,
            instrument.name AS instrumentName,
            oneCase AS currentCase,
            instrument_string.pos AS currentString,
            s.id AS sId,
            ROUND(((12 * octave + (d2.value + oneCase)) - MOD((d2.value + oneCase), 12)) / 12) AS currentOctave,
            (12 * octave + (d2.value + oneCase)) AS currentDigitA,
            MOD((d2.value + oneCase), 12) AS currentDigit,
            (SELECT
                    infoTone
                FROM
                    digit
                WHERE
                    value = MOD((d2.value + oneCase), 12)) AS currentInfoTone,
            IF(d.value = MOD((d2.value + oneCase), 12), i.name, NULL) AS currentIntervale,
            (SELECT
                    ws2.name
                FROM
                    western_system ws2
                WHERE
                    ws.id = ws2.root
                        AND ws2.intervale = i.id
                LIMIT 1) AS wsName
    FROM
        instrument instrument
    LEFT JOIN instrument_string instrument_string ON instrument_string.instrument = instrument.id
    JOIN (SELECT 0 oneCase UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 ) virtualCase
    LEFT JOIN digit d2 ON instrument_string.digit = d2.id
    LEFT JOIN scale s ON s.id = :scaleId
    LEFT JOIN scales_intervales si ON s.id = si.scale_id
    LEFT JOIN intervale i ON i.id = si.intervale_id
    LEFT JOIN western_system ws ON ws.name = :wsName AND ws.intervale = 1
    LEFT JOIN digit d ON d.value = MOD((SELECT
            value
        FROM
            digit
        WHERE
            id = ws.digit) + i.delta, 12)
    WHERE
        instrument.id = :instrumentId
    HAVING currentIntervale IS NOT NULL) r1
        LEFT JOIN
    (SELECT
        f.id AS fId,
            y + stringLoop AS y,
            x + caseLoop - 1 AS x,
            caseLoop AS caseLoopIndex,
            stringLoop AS stringLoopIndex
    FROM
        fingering f
    LEFT JOIN fingering_finger ff ON ff.fingering = f.id
    JOIN (SELECT 0 stringLoop UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) rStringLoop
    JOIN (SELECT 0 caseLoop UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17) rCaseLoop
    GROUP BY f.id , ff.id , stringLoopIndex , caseLoopIndex) r2 ON r2.x = r1.currentCase
        AND r2.y = r1.currentString
GROUP BY fId , stringLoopIndex , caseLoopIndex
HAVING fId IS NOT NULL AND ok IS NOT NULL
        ";

        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping;
        $rsm->addScalarResult('fId', 'fId');
        $rsm->addScalarResult('yList', 'yList');
        $rsm->addScalarResult('xList', 'xList');
        $rsm->addScalarResult('digitAList', 'digitAList');
        $rsm->addScalarResult('intervaleList', 'intervaleList');
        $rsm->addScalarResult('wsNameList', 'wsNameList');


        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter("scaleId",$scale->getId());
        $query->setParameter("instrumentId",$instrument->getId());
        $query->setParameter("wsName",$westernScale->getName());
        return $query->getScalarResult();
    }
}
