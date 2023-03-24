<?php
class Controllercustomuser extends Controller
{
	private $error = array();


	public function add()
	{
		$this->load->language('custom/user');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('custom/user');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_custom_user->add($this->request->post);
			//echo " <script>alert();</script>";
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			/*** This line of code is to redirect to the listing page ***/
			$this->response->redirect($this->url->link('custom/user', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['user_name'])) {
			$data['error_name'] = $this->error['user_name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('custom/user', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['cancel'] = $this->url->link('custom/user', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('custom/user_form', $data));
	}
	public function index()
	{
		$this->load->language('custom/user');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('custom/user');
		$this->getList();
	}


	protected function validate()
	{
		if (!$this->user->hasPermission('modify', 'custom/user')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['user_name']) < 3) || (utf8_strlen($this->request->post['user_name']) > 64)) {
			$this->error['user_name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
	
	protected function getList() {

		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('custom/user', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('custom/user/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('custom/user/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['repair'] = $this->url->link('custom/user/repair', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('custom/userlist', $data));
	}

}
