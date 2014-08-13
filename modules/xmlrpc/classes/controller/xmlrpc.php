<?php defined('SYSPATH') or die('No direct script access.');
// function getTime(){
//     return date('H:i:s');
// }
class Controller_xmlrpc extends Controller {

	public function action_index()
	{
	    $methods = array();
	    foreach(get_class_methods('rpc') as $methodName){
	        $methods['rpc.'.$methodName] = 'rpc:'.$methodName;
	    }
 	    require Kohana::find_file('vendor', 'IXR_Library', 'php');
 	    $ixr = array(
             'rpc.getTime' => 'rpc:getTime',
         );
 	    $server = new IXR_Server($methods);
	}
} // End Welcome
class rpc {
    public function checkaccess($args){
        $this->session = Session::instance();
    	if($this->session->get('user') === NULL){
    		$this->session->set('user', user::instance());
    	}
        if(!$this->session->get('user')->isAdmin()) {
    		return new IXR_Error(-32601, 'Client error. Not Authorized');
    	} else {
    	    return true;
    	}
    }
    public function genCompanyPDF($args){
        $this->checkaccess(false);
        $cid = $args;
        require Kohana::find_file('vendor', 'dompdf/dompdf_config.inc', 'php');
	    $pdf = new DOMPDF;
	    $template = View::factory('katalog/minkatalog');

        $company = Model::factory('company')->get_company_details($cid);
		$booth = Model::factory('company')->get_company_booth($cid);

    	if(count($booth) > 0)
    	    list($company['booth'])    = $booth;
    	else
    	    $company['booth'] = false;


		$exist = Kohana::find_file('../images', 'booth/'.$company['booth']['place'], 'jpg');
		if(!$exist && $company['booth'] !== false)
		    Model::factory('booth')->render_booth($cid);


        $company['branches']             = Model::factory('company')->get_company_branches($cid);
        $company['programs']             = Model::factory('company')->get_company_programs($cid);
        $company['offers']               = Model::factory('company')->get_company_offers($cid);
        $company['cities']               = Model::factory('company')->get_company_cities($cid);
        $company['countries']            = Model::factory('company')->get_company_countries($cid);
        $company['educationtypes']       = Model::factory('company')->get_company_educationtypes($cid);
        $companies[] = $company;

        $template->companies = $companies;
	    $template->renderIndex = false;

	    $pdf->set_base_path(getcwd());
	    $pdf->load_html($template);
	    $pdf->render();
	    Model::factory('status')->set_to_now('lastpregen');
	    if(file_put_contents('pdf/katalog/'.$cid.'.pdf', $pdf->output())){
	        return true;
	    } else {
	        return false;
	    }
    }
    public function getCompanies($args){
        $companies = DB::select('*')
        ->from('company')
        ->order_by('company_id', 'asc')
        ->execute()
        ->as_array();
        return $companies;
    }
}
