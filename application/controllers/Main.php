<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 首页控制文件 ***

创建 2016-07-29 刘深远

*** ***/

class Main extends MY_Controller {

	public function index(){
		$data['need_jsapi'] = 1;

		$this->load->model('banner_model');
		$list = $this->banner_model->getBannerList();
		$data['Banner'] = $list['List'];

		$this->view('index',$data);
	}

	public function fenlei(){
		$data['navSel'] = 2;
		$this->view('fenlei',$data);
	}

	public function fenlei2($id){
		$data['navSel'] = 2;

		$this->load->model('category_model');
		$cate = $this->category_model->getRow($id);
		$data['cate'] = $cate;

		$this->view('fenlei2',$data);
	}

	public function pintuan(){
		$this->load->model('banner_model');
		$list = $this->banner_model->getBannerList();
		$data['Banner'] = $list['List'];

		$this->view('pintuan',$data);
	}

	public function shiyong(){
		$this->load->model('banner_model');
		$list = $this->banner_model->getBannerList();
		$data['Banner'] = $list['List'];

		$this->view('shiyong',$data);
	}

	public function duobao(){
		$this->load->model('banner_model');
		$list = $this->banner_model->getBannerList();
		$data['Banner'] = $list['List'];

		$this->view('duobao',$data);
	}

	public function choujiang(){
		$this->load->model('banner_model');
		$list = $this->banner_model->getBannerList();
		$data['Banner'] = $list['List'];

		$this->view('choujiang',$data);
	}

	public function login(){
		$this->view('login',$data);
	}

	public function out(){
		session_destroy();
		redirect('/');
	}

}