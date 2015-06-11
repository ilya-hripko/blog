<?php
class Front_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_blog_category($id){
        return $this->db->get_where('categories', ['id'=>$id], 1)->row();
    }
    
    public function get_blog_posts_for_category($category_id){
        $this->db->select('P.id as id, P.title as title, P.post_crop as post_crop, P.rating_score as rating_score, P.rating_count as rating_count, U.name as author, U.user_id as user_id, P.created_at as created_at, P.updated_at as updated_at');
        $this->db->join('post2category as P2C', 'P2C.post_id = P.id AND P2C.category_id = '.$category_id);
        $this->db->join('post2user as P2U', 'P2U.post_id = P.id', 'left');
        $this->db->join('users as U', 'P2U.user_id = U.user_id', 'left');
        $this->db->order_by('P.created_at', 'DESC');
        return $this->db->get('post as P')->result();
    }
    
    public function get_post($post_id){
        $this->db->select('P.*, U.name as author, U.user_id as user_id, P2C.category_id as category_id');
        $this->db->join('post2user as P2U', 'P2U.post_id = P.id', 'left');
        $this->db->join('post2category as P2C', 'P2C.post_id = P.id', 'left');
        $this->db->join('users as U', 'P2U.user_id = U.user_id', 'left');
        $this->db->where('P.id', $post_id);
        return $this->db->get('post as P')->row();
    }
    
    public function get_comments_for_post($post_id){
        $this->db->select('C.*');
        $this->db->join('posts2comments as P2C', 'P2C.comment_id = C.id AND P2C.post_id = '.$post_id);
        $this->db->where('C.is_moderated', 1);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('comments as C')->result();
    }
    
    public function get_comments_for_page($page_id){
        $this->db->select('C.*');
        $this->db->join('pages2comments as P2C', 'P2C.comment_id = C.id AND P2C.page_id = '.$page_id);
        $this->db->where('C.is_moderated', 1);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('comments as C')->result();
    }

    public function get_top_menu(){
        return $this->db->order_by('position')
                        ->where('is_menu', 1)
                        ->select('id, url, name')
                        ->get('pages')
                        ->result();
    }


    public function get_page($where = false){
        if($where)
            $this->db->where($where);
        else
            $this->db->where('is_index', 1);
        return $this->db->limit(1)->get('pages')->row();
    }

    public function get_blog_categories($parent_id=0, $limit = false, $where = array(), $select = 'id, name') {
        $this->db->select($select);
        if(!empty($where)) $this->db->where($where);
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('name');
        if($limit)
            $this->db->limit($limit);
        return $this->db->get('categories')->result();
    }
    
    public function add_page_comment($id, $text, $author){
        if($this->db->insert('comments', ['text'=>$text, 'author'=>$author])){
            $comment_id = $this->db->insert_id();
            $this->db->insert('pages2comment', ['page_id'=>$id, 'comment_id'=>$comment_id]);
            return true;
        }
        return false;
    }
    
    public function update_post($set = false, $where = false,$limit = 1){
        if(is_array($set) && is_array($where) && !empty($set) && !empty($where)){
            return $this->db->update('post', $set, $where, $limit);
        }else return false;
    }
    public function update_post2category($set = false, $where = false,$limit = 1){
        if(is_array($set) && is_array($where) && !empty($set) && !empty($where)){
            return $this->db->update('post2category', $set, $where, $limit);
        }else return false;
    }
    public function add_post($post, $category_id, $user_id){
        $this->db->insert('post', $post);
        $post_id = $this->db->insert_id();
        $this->db->insert('post2category', ['post_id'=>$post_id, 'category_id'=>$category_id]);
        $this->db->insert('post2user', ['post_id'=>$post_id, 'user_id'=>$user_id]);
        return $this->get_post($post_id);
    }
    public function delete_post($post_id){
        return $this->db->delete('post', ['id'=>$post_id], 1);
    }
    
}