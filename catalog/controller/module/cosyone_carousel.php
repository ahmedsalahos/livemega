<?php
class ControllerModuleCosyoneCarousel extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');
		
		$title = $setting['title'];
        $data['title'] = $title[$this->config->get('config_language_id')];
		
		$data['columns'] = $setting['grid'];
		
		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['banners'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cosyone_carousel.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/cosyone_carousel.tpl', $data);
		} else {
			return $this->load->view('default/template/module/cosyone_carousel.tpl', $data);
		}
	}
}