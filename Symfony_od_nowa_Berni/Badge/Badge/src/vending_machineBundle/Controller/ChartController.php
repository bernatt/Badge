<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Chart controller.
 *
 * @Route("chart")
 */
class ChartController extends Controller
{
    /**
     * @Route("/generalMoney", name="generalmoney")
     * @Template("@vending_machine/charts/generalMoney.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function generalMoneyChartAction()
    {
        $em = $this->getDoctrine()->getManager();
        $generalServiceRepository = $em->getRepository('vending_machineBundle:typesOfservices');
        $generalServiceCanteen = $generalServiceRepository->findOneById(2);
        $generalServiceDistributor = $generalServiceRepository->findOneById(1);
        $distrMoney = floor($generalServiceDistributor->getCash());
        $canteenMoney = floor($generalServiceCanteen->getCash());



        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Rodzaj usługi', 'Zarobione pieniądze'],
                ['Dystrybutor',     $distrMoney],
                ['Stołówka',      $canteenMoney],

            ]
        );
        $pieChart->getOptions()->setTitle('Udział gałęzi usług w ogólnie generowanym zysku');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(900);
        //$pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->setPieHole(0.3);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(26);


        $canteenRepository =  $em->getRepository('vending_machineBundle:Canteen');
        $vege = $canteenRepository->findOneById(2);
        $meat = $canteenRepository->findOneById(3);
        $vegeCash = floor($vege->getEarnedCash());
        $meatCash = floor($meat->getEarnedCash());


        $pieChartCanteen = new PieChart();
        $pieChartCanteen->getData()->setArrayToDataTable(
            [['Rodzaj posiłku', 'Zarobione pieniądze'],
                ['Wegetariańskie',     $vegeCash],
                ['Mięsne',      $meatCash],

            ]
        );
        $pieChartCanteen->getOptions()->setTitle('Udział rodzai posiłków w generowanym przez stołówkę zysku');
        $pieChartCanteen->getOptions()->setHeight(400);
        $pieChartCanteen->getOptions()->setWidth(900);
        //$pieChartCanteen->getOptions()->setIs3D(true);
        $pieChartCanteen->getOptions()->setPieHole(0.3);
        $pieChartCanteen->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChartCanteen->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChartCanteen->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChartCanteen->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChartCanteen->getOptions()->getTitleTextStyle()->setFontSize(26);


        return [
            'piechart' => $pieChart,
            'piechartcanteen' => $pieChartCanteen,
        ];
    }

    /**
     * @Route("/bestsellers", name="bestsellers")
     * @Template("@vending_machine/charts/bestsellers.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function bestsellersChartAction()
    {
        $doctrine = $this->getDoctrine();
        $products = $doctrine->getRepository('vending_machineBundle:machine')->findOrderedByNumberOfSold();

        $retArr = [['Produkt', 'Ilość sprzedanych']];

        for($i=0;$i<count($products);$i++){
            $retArr[] = [$products[$i]->getProductName() , $products[$i]->getNumberOfSold()];
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            $retArr
        );

        $pieChart->getOptions()->setTitle('Dziesięć najlepiej sprzedających się produktów');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(26);


        return [
            'piechart' => $pieChart
        ];
    }
}
