<?php
class Alist extends CI_Model {
    public function __construct(){ parent::__construct(); }

    public function render($title, $db, $path, $hasNewButton = true, $cond = array(), $order = array(), $buttons = array(), $original = true)
    {
        return [
            "title" => $title,
            "hasnewbtn" => $hasNewButton,
            "newbtn" => '<a href="admin/' . $path . '/create/-1" class="btn btn-outline-secondary"><i class="fa fa-fw fa-plus"></i> Új</a>',
            "html" => $this->createTableStructure($db, $path, $cond, $order, $buttons, $original)
        ];
    }


    private function createTableStructure($db, $path, $cond, $order, $buttons, $original){
        $_fields = $this->db->list_fields($db);
        $html = '<table class="table"><thead><tr>';
        foreach($_fields as $_field)
        {
            $field = $this->db->select('*')->from('options')->where('_key',$_field)->get()->result_array();
            if(@$field[0] && @$field[0]['_listable'] == 1){
                $html .= '<th>' . $field[0]['_value'] . '</th>';
            };
        };
        $html .= '<th>&nbsp;</th></tr><thead><tbody>';
        $this->db->select('*');
        $this->db->from($db);
        if(!empty($cond)){foreach($cond as $field=>$param){
            $this->db->where($field,$param);
        };};
        if(!empty($order)){foreach($order as $field=>$param){
            $this->db->order_by($field,$param);
        };};
        $rows = $this->db->get()->result_array();
        foreach($rows as $row){
            $html .= '<tr>';
            foreach($_fields as $_field)
            {
                $field = $this->db->select('_listable,_specificValue')->from('options')->where('_key',$_field)->get()->result_array();
                if(@$field[0] && @$field[0]['_listable'] == 1){
                    if($field[0]['_specificValue'] == null){
                        $html .= '<td>'.$row[$_field].'</td>';
                    }else{
                        $html .= '<td>'.$this->makeSpecificValue($field[0]['_specificValue'], $row[$_field]). '</td>';
                    }
                };
            }
            $html .= '<td><div class="btn-group">' . $this->renderButtons($row['id'], $path, $buttons, $original) . '</div></td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        return $html;
    }
    private function makeSpecificValue($_sv, $value)
    {
        $sv = explode(':', $_sv);
        if($sv[0] == "db"){
            $item = $this->db->select($sv[2])->from($sv[1])->where($sv[3],$value)->get()->result_array()[0][$sv[2]];
            return $item;
        }
    }
    private function renderButtons($id, string $path, array $buttons = array(), bool $showOriginal = true)
    {
        $html = '';
        if($showOriginal){
            $html .= '<a href="admin/'.$path.'/edit/'.$id.'" class="btn btn-outline-warning"><i class="fa fa-fw fa-pencil"></i></a>';
            $html .= '<a href="admin/'.$path.'/delete/'.$id.'" class="btn btn-outline-danger"><i class="fa fa-fw fa-trash"></i></a>';
        };
        foreach($buttons as $button){
            $html .= '<a href="admin/'.$path.'/'.$button[0].'/'.$id.'" class="btn btn-outline-'.$button[1].'"><i class="fa fa-fw fa-'.$button[2].'"></i></a>';
        }

        return $html;
    }
}