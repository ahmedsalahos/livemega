<?php
class ControllerModuleCosyoneTagcloud extends Controller {
	public function index() {
		
		$this->load->model('localisation/language');
		$this->load->model('module/cosyone_tagcloud');
		
		$title = $this->config->get('cosyone_tagcloud_title');
        $data['module_title'] = $title[$this->language->get('code')];
	
		$data['limit'] = $this->config->get('cosyone_tagcloud_limit');	
		
		$data['tagcloud'] = $this->model_module_cosyone_tagcloud->getRandomTags(array(
			'limit'   => (int)$data['limit']
		));
			
       if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cosyone_tagcloud.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/cosyone_tagcloud.tpl', $data);
			} else {
				return $this->load->view('default/template/module/cosyone_tagcloud.tpl', $data);
			}
	}
}