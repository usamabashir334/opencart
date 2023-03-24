<?php
use Symfony\Component\Validator\Constraints\EqualTo;
class ControllerExtensionModuleCruduser extends Controller {
	private $error = array();

	public function install() {
		$this->load->model('extension/module/cruduser');

		$this->model_extension_module_cruduser->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/cruduser');

		$this->model_extension_module_cruduser->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/cruduser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/cruduser');

		//$this->getList();
	}

	public function add() {
		$this->load->language('extension/module/cruduser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/cruduser');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_cruduser->addCruduser($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/cruduser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/cruduser');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_cruduser->editCruduser($this->request->get['cruduser_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/module/cruduser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/cruduser');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $cruduser_id) {
				$this->model_extension_module_cruduser->deleteCruduser($cruduser_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/module/cruduser/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/cruduser/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['crudusers'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$cruduser_total = $this->model_extension_module_cruduser->getTotalCruduser();

		$results = $this->model_extension_module_cruduser->getCruduser($filter_data);

		foreach ($results as $result) {
			$data['crudusers'][] = array(
				'cruduser_id'        => $result['cruduser_id'],
				'cruduser_name'      => $result['cruduser_name'],
				'date_added'         => $result['date_added'],
				'sort_order'         => $result['sort_order'],
				'edit'               => $this->url->link('extension/module/cruduser/edit', 'user_token=' . $this->session->data['user_token'] . '&cruduser_id=' . $result['cruduser_id'] . $url, true)
			);
		}

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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_cruduser_name'] = $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . '&sort=cruduser_name' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $cruduser_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cruduser_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cruduser_total - $this->config->get('config_limit_admin'))) ? $cruduser_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cruduser_total, ceil($cruduser_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/cruduser_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['cruduser_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['cruduser_name'])) {
			$data['error_name'] = $this->error['cruduser_name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['phone_number'])) {
			$data['error_phone'] = $this->error['phone_number'];
		} else {
			$data['error_phone'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['cruduser_id'])) {
			$data['action'] = $this->url->link('extension/module/cruduser/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/module/cruduser/edit', 'user_token=' . $this->session->data['user_token'] . '&cruduser_id=' . $this->request->get['cruduser_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/module/cruduser', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['cruduser_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$Cruduser_info = $this->model_extension_module_cruduser->getCruduserInfo($this->request->get['cruduser_id']);
		}

		
		if (isset($this->request->post['cruduser_name'])) {
			$data['cruduser_name'] = $this->request->post['cruduser_name'];
		} elseif (!empty($Cruduser_info)) {
			$data['cruduser_name'] = $Cruduser_info['cruduser_name'];
		} else {
			$data['cruduser_name'] = '';
		}

		if (isset($this->request->post['phone_number'])) {
			$data['phone_number'] = $this->request->post['phone_number'];
		} elseif (!empty($Cruduser_info)) {
			$data['phone_number'] = $Cruduser_info['phone_number'];
		} else {
			$data['phone_number'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($Cruduser_info)) {
			$data['sort_order'] = $Cruduser_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/cruduser_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/cruduser')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['cruduser_name']) < 3) || (utf8_strlen($this->request->post['cruduser_name']) > 25)) {
			$this->error['cruduser_name'] = $this->language->get('error_name');
		}
		
		if ((utf8_strlen($this->request->post['phone_number']) < 10) || (utf8_strlen($this->request->post['phone_number']) > 13)) {
			$this->error['phone_number'] = $this->language->get('error_phone');

		}else if(!preg_match("/^((\+92)?(92)?(0)?)(3)([0-9]{9})$/i", $this->request->post['phone_number']) ) {
            $this->error['phone_number'] = $this->language->get('error_phone_for');
        }
		
		

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/cruduser')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('extension/module/cruduser');
		return !$this->error;
	}
}
