<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends RESTRICTED_Controller {

    var $data = array();
    
    function __construct() {
        parent::__construct();
        $this->data['base_url'] = $this->base_url;
        
        $this->load->library('user_agent');
        $this->load->model('front_model');
        $this->data['blog_categories'] = $this->front_model->get_blog_categories(0);
        $this->data['top_menu'] = $this->front_model->get_top_menu();
        
    }
    
       
    public function contacts(){
        $this->_fix_url_slash();
        $this->data['page']='page_contacts';
        $page = $this->front_model->get_page(['url'=>'contacts']);
        $this->data['title'] = $page->title;
        $this->data['description'] = $page->description;
        $this->data['keywords'] = $page->keywords;
        $this->data['name'] = $page->name;
        $this->data['h1'] = $page->h1;
        $this->data['text_html'] = $page->text;
        $this->data['page_id'] = $page->id;
        $this->data['this_url'] = $this->base_url.$page->url.'/';
        
        $this->base_front_view('page_contacts', $this->data);
    }
    
    public function index($is_page = false) {
        $this->_fix_url_slash();
        
        if($is_page){
            $this->data['page']='page';
            $url = $this->uri->uri_string();
            $page = $this->front_model->get_page(['url'=>$url]);
        }else{
            $this->data['page']='index';
            $page = $this->front_model->get_page();
        }
        if(!$page){
            $this->error_404();
            return;
        }
        $this->add_modified_header($page->updated_at);
        $this->data['title'] = $page->title;
        $this->data['description'] = $page->description;
        $this->data['keywords'] = $page->keywords;
        $this->data['name'] = $page->name;
        $this->data['h1'] = $page->h1;
        $this->data['text_html'] = $page->text;
        $this->data['comments'] = $this->front_model->get_comments_for_page($page->id);
        $this->data['page_id'] = $page->id;
        $this->data['this_url'] = $this->base_url.$page->url.'/';
        
        $this->base_front_view('page', $this->data);
    }
    
    public function blogCategory($id, $friendly_url){
        $this->_fix_url_slash();
        
        $id=(int)$id;
        
        $category = $this->front_model->get_blog_category($id);
        if(!$category){
            redirect(base_url(), 'location', 301);
            return;
        }
        if(friendly_url($category->name)!=$friendly_url){
            redirect(generate_url('blogCategory', array('name' => $category->name, 'id' => $category->id), $this->base_url), 'location', 301);
            return;
        }
        
        $this->data['page'] = 'blogCategory';
        $this->data['title'] = $category->seo_title;
        $this->data['description'] = $category->seo_description;
        $this->data['keywords'] = $category->seo_keywords;
        $this->data['name'] = $category->name;
        $this->data['h1'] = $category->name;
        $this->data['text_html'] = $category->text;
        $this->data['this_url'] = generate_url('blogCategory', array('name' => $category->name, 'id' => $category->id), $this->base_url);
        
        $this->data['posts'] = $this->front_model->get_blog_posts_for_category($category->id);
        if(!empty($this->data['posts'])){
            $this->add_modified_header($this->data['posts'][0]->updated_at);
        }
        
        $this->base_front_view('category', $this->data);
    }
    
    public function blogPost($id, $friendly_url){
        $this->_fix_url_slash();
        
        $id=(int)$id;
        
        $post = $this->front_model->get_post($id);
        if(!$post){
            redirect(base_url(), 'location', 301);
            return;
        }
        if(friendly_url($post->title)!=$friendly_url){
            redirect(generate_url('blogPost', ['title'=>$post->title, 'id'=>$post->id], $this->base_url), 'location', 301);
            return;
        }
        
        $this->add_modified_header($post->updated_at);
        
        $this->data['page'] = 'blogPost';
        $this->data['title'] = $post->seo_title;
        $this->data['description'] = $post->seo_description;
        $this->data['keywords'] = $post->seo_keywords;
        $this->data['name'] = $post->title;
        $this->data['h1'] = $post->title;
        $this->data['text_html'] = $post->post;
        $this->data['author'] = $post->author;
        $this->data['created_at'] = $post->created_at;
        $this->data['post'] = $post;
        $this->data['this_url'] = generate_url('blogPost', ['title'=>$post->title, 'id'=>$post->id], $this->base_url);
        
        $this->base_front_view('post', $this->data);
    }
         
    
    function error_404($title = 'Not Found (#404)', $message = 'Page not found') {
        set_status_header(404);
        $is_backend = ($this->input->get('xhr') && $this->input->get('key') ? 1: 0);
        $this->data['page'] = 'error_404';
        $this->data['title'] = $title;
        $this->data['message'] = $message;
        
        $this->base_front_view('error', $this->data);
    }

}