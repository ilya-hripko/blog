<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends RESTRICTED_Controller {

    var $data = array();
    
    function __construct() {
        parent::__construct();
        $this->data['base_url'] = $this->base_url;
        $this->load->model('front_model');
    }
    
    function add_comment(){
        
        if(!$this->input->is_ajax_request()){
            die('Hacker He-He!');
        }
        
        $this->load->library('validation');
        
        $this->validation->set_rules('name', 'Name', 'trim|required|max_length[50]');
        $this->validation->set_rules('comment', 'Comment', 'trim|required|min_length[20]|max_length[5000]');
        if (!$this->validation->run()) {
            $errors = $this->validation->error_string('<b>','<b><br/>');
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'errors'  => $errors
            ]));
            return;
        }     
        $testimonial = $this->input->post('comment');
        $author = $this->input->post('name');
        $type = $this->input->post('type');
        $id = (int)$this->input->post('id');
        if($id>0 && in_array($type, ['post', 'page'])){
            if($type=='post'){
                if(!$this->front_model->add_post_comment($id, $comment, $author)){
                    $error = '<b>Internal server error!</b>';
                }
            }else{
                if(!$this->front_model->add_page_comment($id, $comment, $author)){
                    $error = '<b>Internal server error!</b>';
                }
            }
        }else{
            $error = '<b>Internal server error!</b>';
        }
        
        if(isset($error)){
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'success' => false,
                'errors'  => $error
            ]));
            return;
        }
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'success' => true
        ]));
        return;
    }
    
    public function post_rating(){
        if(!$this->input->is_ajax_request()){
            die('Hacker He-He!');
        }
        $this->load->library('validation');
        
        $this->validation->set_rules('post_id', 'Post', 'required|integer');
        $this->validation->set_rules('score', 'Score', 'required');
        $this->validation->set_rules('votes', 'Votes', 'required|integer');
        if (!$this->validation->run()) {
            $errors = $this->validation->error_string('<b>','<b><br/>');
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ERR',
                'msg'  => $errors
            ]));
            return;
        }     
        $post_id = (int)$this->input->post('post_id');
        
        // check session
        $this->load->library('user_agent');
        $posts = $this->session->userdata('posts');
        if(!$posts){
            $posts = [];
        }
        if(!in_array($post_id, $posts)){
            array_push($posts, $post_id);
            $post = $this->front_model->get_post($post_id);
            if($post){
                $score = (float)$this->input->post('score');
                $rating_count = $post->rating_count+1;
                $rating_score = ($post->rating_score*$post->rating_count + $score)/$rating_count;
                $this->front_model->update_post(['rating_score'=>$rating_score, 'rating_count'=>$rating_count],['id'=>$post_id],1);
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'status' => 'OK',
                    'msg'  => 'Thank you. Your vote has been saved!'
                ]));
            }
            $this->session->set_userdata('posts',$posts);
        }else{
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'ERR',
                'msg'  => 'You have already voted for this post!'
            ]));
            return;
        } 
    }
    public function post_form(){
        if(!$this->input->is_ajax_request() && $this->user_id){
            die('Hacker He-He!');
        }
        $data = array();
        if($this->input->post('post_id')){
            $data['post'] = $this->front_model->get_post((int)$this->input->post('post_id'));
            if(!$data['post']){
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'status' => 'error',
                    'error'  => 'Wrong post ID!'
                ]));
                return;
            }
        }
        $data['categories'] = $this->front_model->get_blog_categories(0);
            
        
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => 'success',
            'html'  => load_view('front/ajax/post_form', $data, true)
        ]));
        return;
    }
    
    public function edit_post(){
        if(!$this->input->is_ajax_request() || !$this->user_id){
            die('Hacker He-He!');
        }
        $this->load->library('validation');
        
        
        $this->validation->set_rules('category_id', 'Category', 'required|integer');
        $this->validation->set_rules('title', 'Title', 'required|max_length[255]|min_length[5]');
        $this->validation->set_rules('post_crop', 'Post Crop', 'required|max_length[1000]|min_length[100]');
        $this->validation->set_rules('post', 'Post', 'required|max_length[5000]|min_length[100]');
        
        
        if (!$this->validation->run()) {
            $errors = $this->validation->error_string('<b>','<b><br/>');
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'error',
                'errors'  => $errors
            ]));
            return;
        }  
        if($this->input->post('post_id')){
            $post_id = (int)$this->input->post('post_id');
            $post = $this->front_model->get_post($post_id);
            if(!$post){
                $this->output->set_content_type('application/json')->set_output(json_encode([
                    'status' => 'error',
                    'errors'  => 'Not existed Post ID'
                ]));
                return;
            }
            if((int)$this->input->post('category_id')>0 && $post->category_id != (int)$this->input->post('category_id')){
                $this->front_model->update_post2category(['category_id'=>(int)$this->input->post('category_id')],['post_id'=>$post_id],1);
            }
            $this->front_model->update_post([
                'title'=>$this->input->post('title'),
                'seo_title'=>$this->input->post('seo_title'),
                'seo_description'=>$this->input->post('seo_description'),
                'seo_keywords'=>$this->input->post('seo_keywords'),
                'post_crop'=>$this->input->post('post_crop'),
                'post'=>$this->input->post('post'),
                'updated_at'=>time()
            ], ['id'=>$post_id], 1);
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'success',
                'url'  => generate_url('blogPost', ['title'=>$this->input->post('title'), 'id'=>$post_id], $this->base_url)
            ]));
            return;
        }else{
            $insert_data = [
                'title'=>$this->input->post('title'),
                'seo_title'=>$this->input->post('seo_title'),
                'seo_description'=>$this->input->post('seo_description'),
                'seo_keywords'=>$this->input->post('seo_keywords'),
                'post_crop'=>$this->input->post('post_crop'),
                'post'=>$this->input->post('post'),
                'updated_at'=>time(),
                'created_at'=>time()
            ];
            $post = $this->front_model->add_post($insert_data, (int)$this->input->post('category_id'), $this->user_id);
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'success',
                'url'  => generate_url('blogPost', ['title'=>$post->title, 'id'=>$post->id], $this->base_url)
            ]));
            return;
        }
    }
    
    public function delete_post(){
        if(!$this->input->is_ajax_request() || !$this->user_id){
            die('Hacker He-He!');
        }
        $post_id = (int)$this->input->post('post_id');
        $post = $this->front_model->get_post($post_id);
        if(!$post || $post->user_id != $this->user_id){
            $this->output->set_content_type('application/json')->set_output(json_encode([
                'status' => 'error',
                'errors'  => 'You haven\'t permissions to delete this post!'
            ]));
            return;
        }
        $this->front_model->delete_post($post_id);
        $category = $this->front_model->get_blog_category($post->category_id);
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => 'success',
            'url'  => generate_url('blogCategory', ['name'=>$category->name, 'id'=>$category->id], $this->base_url)
        ]));
        return;
    }
}