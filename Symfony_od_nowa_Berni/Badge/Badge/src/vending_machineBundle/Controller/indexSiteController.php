<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class indexSiteController extends Controller
{
    /**
     * @Route("/" , name="homepage")
     * @Template("@vending_machine/base.html.twig")
     */

    public function homepageAction()
    {
        return [];
    }
}
