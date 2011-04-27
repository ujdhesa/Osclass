<?php

class Pagination {

    private $total;
    private $selected;
    private $class_first;
    private $class_last;
    private $class_prev;
    private $class_next;
    private $text_first;
    private $text_last;
    private $text_prev;
    private $text_next;
    private $class_selected;
    private $class_non_selected;
    private $delimiter;
    private $force_limits;
    private $sides;
    private $url;

    public function __construct($params = null) {
        $this->total = (isset($params['total']))?$params['total']:osc_search_total_pages();
        $this->selected = (isset($params['selected']))?$params['selected']:osc_search_page();
        $this->class_first = (isset($params['class_first']))?$params['class_first']:'searchPaginationFirst';
        $this->class_last = (isset($params['class_last']))?$params['class_last']:'searchPaginationLast';
        $this->class_prev = (isset($params['class_prev']))?$params['class_prev']:'searchPaginationPrev';
        $this->class_next = (isset($params['class_next']))?$params['class_next']:'searchPaginationNext';
        $this->text_first = (isset($params['text_first']))?$params['text_first']:'&laquo;';
        $this->text_last = (isset($params['text_last']))?$params['text_last']:'&raquo';
        $this->text_prev = (isset($params['text_prev']))?$params['text_prev']:'&lt;';
        $this->text_next = (isset($params['text_next']))?$params['text_next']:'&gt;';
        $this->class_selected = (isset($params['class_selected']))?$params['class_selected']:'searchPaginationSelected';
        $this->class_non_selected = (isset($params['class_non_selected']))?$params['class_non_selected']:'searchPaginationNonSelected';
        $this->delimiter = (isset($params['delimiter']))?$params['delimiter']:" ";
        $this->force_limits = (isset($params['force_limits']))?(bool)$params['delimiter']:false;
        $this->sides = (isset($params['sides']))?$params['sides']:2;
        $this->url = (isset($params['url']))?$params['url']:osc_update_search_url(array('iPage' => '{PAGE}'));
    }

    public function get_raw_pages($params = null) {
        $pages = array();
        
        $pages['first'] = 0;//$this->paginate_link(0,$this->class_first);
        $pages['prev'] = ($this->selected>0)?$this->selected-1:'';//($this->selected>0)? $this->paginate_link($this->selected - 1,$this->class_prev) : '';
        
        for($p = ($this->selected-$this->sides);$p<$this->selected;$p++) {
            if($p>=0) {
                $pages['pages'][] = $p;//$this->paginate_link($p, $this->class_non_selected);
            }
        }

        $pages['pages'][] = $this->selected;//$this->paginate_link($this->selected, $this->class_selected);

        for($p = ($this->selected+1);$p<=($this->selected+$this->sides);$p++) {
            if($p<$this->total) {
                $pages['pages'][] = $p;//$this->paginate_link($p, $this->class_non_selected);
            }
        }

        $pages['next'] = ($this->selected<($this->total-1))? $this->selected+1:'';//$this->paginate_link($this->selected + 1,$this->class_next) : '';
        $pages['last'] = $this->total-1;//($this->selected<($this->total))? $this->paginate_link($this->total-1, $this->class_last) : '';
        return $pages;
    }
    
    public function get_pages() {
        
        $pages = $this->get_raw_pages();
    
        if(!$this->force_limits) {
            if($pages['first']==$pages['pages'][0]) {
                unset($pages['first']);
            }
            if($pages['last']==$pages['pages'][count($pages['pages'])-1]) {
                unset($pages['last']);
            }
        }
        if($pages['prev']==='') {
            unset($pages['prev']);
        }
        if($pages['next']==='') {
            unset($pages['next']);
        }
        
        return $pages;
        
    }
    
    public function get_links() {
        $pages = $this->get_pages();
        $links = array();
        if(isset($pages['first'])) {
            $links[] = '<a class="' . $this->class_first . '" href="' . str_replace(urlencode('{PAGE}'), $pages['first'], $this->url) . '">' . $this->text_first . '</a>';
        }
        if(isset($pages['prev'])) {
            $links[] = '<a class="' . $this->class_prev . '" href="' . str_replace(urlencode('{PAGE}'), $pages['prev'], $this->url) . '">' . $this->text_prev . '</a>';
        }
        foreach($pages['pages'] as $p) {
            if($p==$this->selected) {
                $links[] = '<a class="' . $this->class_selected . '" href="' . str_replace(urlencode('{PAGE}'), $p, $this->url) . '">' . ($p + 1) . '</a>';
            } else {
                $links[] = '<a class="' . $this->class_non_selected . '" href="' . str_replace(urlencode('{PAGE}'), $p, $this->url) . '">' . ($p + 1) . '</a>';
            }
        }
        if(isset($pages['next'])) {
            $links[] = '<a class="' . $this->class_next . '" href="' . str_replace(urlencode('{PAGE}'), $pages['next'], $this->url) . '">' . $this->text_next . '</a>';
        }
        if(isset($pages['last'])) {
            $links[] = '<a class="' . $this->class_last . '" href="' . str_replace(urlencode('{PAGE}'), $pages['last'], $this->url) . '">' . $this->text_last . '</a>';
        }
        
        return $links;
        
    }
    
    
    public function pagination() {
        $links = $this->get_links();
        return implode($this->delimiter, $links);
    }

}
  
?> 