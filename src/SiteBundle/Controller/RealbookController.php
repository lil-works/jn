<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Realbook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\Tune;
use AppBundle\Entity\TunesRealbooks;
use SiteBundle\Filter\TuneFilter;

class RealbookController extends Controller
{
    /**
     * Lists all Descriptor entities.
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();



        $dql = "
            SELECT t.title, GROUP_CONCAT(r.name) as realbookNameList ,GROUP_CONCAT(r.id) as realbookIdList FROM AppBundle:Tune t
            LEFT JOIN AppBundle:TunesRealbooks tr WITH tr.tune = t.id
            LEFT JOIN AppBundle:Realbook r WITH tr.realbook = r.id
            WHERE t.title
            LIKE :title
            GROUP BY t.id
              ";

        $query = $em->createQuery($dql);
        $query->setParameter('title', '%%');


        $form = $this->get('form.factory')->create(TuneFilter::class);

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $data = $form->getData();
            $query->setParameter('title',"%" . $data['title'] . "%");
        }



        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('SiteBundle:Realbook:index.html.twig',array(
            'pagination'=>$pagination,
            'form'=>$form->createView()
        ));
    }

    /**
     * Finds and displays a Author entity.
     * @ParamConverter("realbook", class="AppBundle\Entity\Realbook",options={"mapping": {"realbook": "name"  }})
     * @ParamConverter("tune", class="AppBundle\Entity\Tune",options={"mapping": {"tune": "title"  }})
     */
    public function downloadAction(Request $request,Realbook $realbook,Tune $tune)
    {

        $em = $this->getDoctrine()->getManager();
        $versions = $em->getRepository('AppBundle:Tune')->findVersions($tune,$realbook);

        $dql = "
            SELECT tr  FROM AppBundle:TunesRealbooks tr
            WHERE tr.tune = :tune AND tr.realbook = :realbook
              ";
        $query = $em->createQuery($dql);
        $query->setMaxResults(1);
        $query->setParameter('tune', $tune->getId());
        $query->setParameter('realbook', $realbook->getId());

        $page = $query->getResult();
        $page = $page[0]->getPage() + $realbook->getFirstPage() -1 ;

        $img = $realbook->getFileName()."-".$page."_1.png";
        $filename = $this->container->get('kernel')->locateResource('@SiteBundle/Resources/public')."/realbooks/".$img;
        $content = file_get_contents($filename);
        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$realbook->getName()."-".$tune->getTitle().".".$realbook->getImageType());

        $response->setContent($content);
        return $response;
    }
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("realbook", class="AppBundle\Entity\Realbook",options={"mapping": {"realbook": "name"  }})
     * @ParamConverter("tune", class="AppBundle\Entity\Tune",options={"mapping": {"tune": "title"  }})
     */
    public function tuneAction(Request $request,Realbook $realbook,Tune $tune)
    {
        $em = $this->getDoctrine()->getManager();
        $versions = $em->getRepository('AppBundle:Tune')->findVersions($tune,$realbook);

        $dql = "
            SELECT tr  FROM AppBundle:TunesRealbooks tr
            WHERE tr.tune = :tune AND tr.realbook = :realbook
              ";
        $query = $em->createQuery($dql);
        $query->setMaxResults(1);
        $query->setParameter('tune', $tune->getId());
        $query->setParameter('realbook', $realbook->getId());

        $page = $query->getResult();
        $page = $page[0]->getPage() + $realbook->getFirstPage() -1 ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle( $seoPage->getTitle() . " • " . $tune->getTitle() . " " . $realbook->getName())
            ->addMeta('name', 'description', "search tune in the realbooks")
        ;

        $img = $realbook->getFileName()."-".$page."_1.png";
        return $this->render('SiteBundle:Realbook:tune.html.twig',array(
            "img"=>$img,
            "versions"=>$versions,
            "tune"=>$tune,
            "realbook"=>$realbook
        ));
    }
    public function generateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $realbooks = $em->getRepository('AppBundle:Realbook')->findAll();

        $path = $this->get('kernel')->locateResource('@SiteBundle/Resources/public/realbooks/tune-indexs.html');
        $html = htmlentities(file_get_contents($path));

        $crawler = new Crawler($html);
        $txt = $crawler->filter('body ');
        $arrayTxt = explode("\n",strip_tags($txt->text()));


        $expulse = array("","Master Index","Song Title","Book","Page");
        $arrayTxt = array_diff($arrayTxt,$expulse);

        $output = array();
        $i = 0;

        foreach($arrayTxt as $value){
            if(!in_array($value,$expulse)){

                if  ($i % 3 == 0){
                    //$value = preg_replace('/[^A-Za-z0-9\-]/', '', $value);
                    $value = str_replace("&#160","",$value);
                    $value = str_replace("/"," ",$value);
                   $tmp["title"]=$value;
               }elseif($i % 3 == 1){
                   $tmp["realbook"]=$value;
               }elseif($i % 3 == 2){

                   $tmp["page"]=$value;
                   array_push($output,$tmp);
                    // le realbook existe-il?
                   if($realbook =  $em->getRepository('AppBundle:Realbook')->findOneByIndexName($tmp["realbook"])){

                       if(!$tune = $em->getRepository('AppBundle:Tune')->findOneByTitle($tmp["title"])){
                           $tune = new Tune();
                           $tune->setTitle($tmp["title"]);

                           $em->persist($tune);

                       }

                       if(!$tuneRealbook = $em->getRepository('AppBundle:TunesRealbooks')->findOneBy(
                           array(
                               "tune"=>$tune,
                               "realbook"=>$realbook
                           ))){

                           $tuneRealbook = new TunesRealbooks();
                           $tuneRealbook->setRealbook($realbook);
                           $tuneRealbook->setTune($tune);
                           $tuneRealbook->setPage($tmp["page"]);
                           $em->persist($tuneRealbook);
                       }
                   }

                   $tmp = array();
               }
            $i++;
            }


        }
        $em->flush();
        return $this->render('SiteBundle:Realbook:generate.html.twig',array(
            'realbooks'=>$realbooks,
            'txt'=>$output
        ));
    }
}