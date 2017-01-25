<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
	
	public function Footer() {

        $this->Cell(0, 5, 'Page '.$this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
	/*public function Header()
	{
		 $img = base_url().'assets/images/trexco_banner.jpg';
		 $this->writeHTML('<div style="height:70px;clear:both;"><img src="'.$img.'"></div>', true, false, false, false, '');
	}*/

}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */