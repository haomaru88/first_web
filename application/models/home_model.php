<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//	$new_line = '<br>';
$new_line = "\r\n";

class Home_model extends CI_Model
{
   /**
     * 테이블명
     */
   protected $table;
   protected $table_index;

   /**
     * 프라이머리 키 컬럼
     */
   protected $primary_key;

   /**
     * 메타데이터 키 컬럼
     */
   protected $meta_name_key;

   /**
     * 메타데이터 값 컬럼
     */
   protected $meta_value_key;

   /**
     * json형태로 저장할 컬럼
     */
   protected $json_key;

   protected $mysqli;

   public function __construct()
   {
      parent::__construct();

      $this->table = 'table_data';
      $this->table_index = 'table_index';

      $this->mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');
   }

   /*
     * 데이블 정보 세팅
     */
   public function _INITIALIZE($config = array())
   {
      $this->db->reset_query();
      $this->table = false;
      $this->primary_key = false;
      $this->meta_name_key = false;
      $this->meta_value_key = false;
      $this->json_key = false;
      if (!empty($config)) {
         foreach ($config as $key => $value) {
            $this->{$key} = $value;
         }
      }
   }

   /*
     * 데이터 등록
     */
   public function _INSERT($data)
   {
      if (empty($this->table) || empty($this->primary_key) || empty($data)) {
         echo "CIV_MODEL _INSERT ERROR";
         exit;
      }
      
      foreach ($data as $key => $value) {
         //json_encode
         if (!empty($this->json_key) && in_array($key, $this->json_key) && !empty($value)) {
            $data[$key] = json_encode($value);
         }
      }

      $this->db->reset_query();
      $this->db->set($data);
      $result = $this->db->insert($this->table);
      return $result;
   } 

   /*
     * 데이터 등록 후 primary key 반환
     */
   public function _INSERT_PK($data) {
      $result = $this->_INSERT($data);
      if ($result) {
         return $this->db->insert_id();
      } else {
         return false;
      }
   }

   /*
     * 데이터 수정
     */
   public function _UPDATE($data, $primary_key = '', $where = array(), $freewhere = '', $sop = 'AND')
   {
      if (empty($this->table) 
         || empty($this->primary_key) 
         || empty($data) 
         || (empty($primary_key) && empty($where) && empty($freewhere))
      ) {
         echo "CIV_MODEL _UPDATE ERROR";
         exit;
      }

      foreach ($data as $key => $value) {
         //json_encode
         if (!empty($this->json_key) && in_array($key, $this->json_key) && !empty($value)) {
            $data[$key] = json_encode($value);
         }
      }

      $this->db->reset_query();

      if (!empty($primary_key)) {
         $where[$this->primary_key] = $primary_key;
      }
      if (!empty($where)) {
         if (strtoupper($sop) == "AND") {
            $this->db->where($where);
         } else {
            $this->db->or_where($where);
         }
      }
      if (!empty($freewhere)) {
         $this->db->where($freewhere, null, false);
      }

      $result = $this->db->update($this->table, $data);
      return $result;
   } 

   /*
     * 데이터 삭제
     */
   public function _DELETE($primary_key = '', $where = array(), $freewhere = '', $sop = 'AND')
   {
      if (empty($this->table) 
         || empty($this->primary_key) 
         || (empty($primary_key) && empty($where) && empty($freewhere))
      ) {
         echo "CIV_MODEL _DELETE ERROR";
         exit;
      }
      $this->db->reset_query();

      if (!empty($primary_key)) {
         $where[$this->primary_key] = $primary_key;
      }
      if (!empty($where)) {
         if (strtoupper($sop) == "AND") {
            $this->db->where($where);
         } else {
            $this->db->or_where($where);
         }
      }
      if (!empty($freewhere)) {
         $this->db->where($freewhere, null, false);
      }

      $result = $this->db->delete($this->table);
      return $result;
   } 

   /*
     * SQL 조건 설정
     */   
   public function _SET_TERMS($terms, $sop = "AND") {
      if (!empty($terms)) {
         foreach ($terms as $key => $value) {
            if (!empty($value)) {
               switch ($key) {
                     case 'select':
                        $this->db->select($value);
                        break;
                     case 'join':
                        if (!empty($value['table']) && !empty($value['on'])) {
                           $value['type'] = isset($value['type']) ? $value['type'] : "INNER";
                           $this->db->join($value['table'], $value['on'], $value['type']);
                        } else {
                           foreach ($value as $key2 => $join) {
                              $join['type'] = isset($join['type']) ? $join['type'] : "INNER";
                              $this->db->join($join['table'], $join['on'], $join['type']);
                           }
                        }
                        break;
                     case 'where':
                        $this->db->group_start();
                        if (strtoupper($sop) == "AND") {
                           $this->db->where($value);
                        } else {
                           $this->db->or_where($value);
                        }           
                        $this->db->group_end();
                        break;
                     case 'freewhere':
                        $this->db->group_start();
                        if (strtoupper($sop) == "AND") {
                           $this->db->where($value, null, false);
                        } else {
                           $this->db->or_where($value, null, false);
                        }           
                        $this->db->group_end();
                        break;
                     case 'like':
                        $this->db->group_start();
                        if (strtoupper($sop) == "AND") {
                           $this->db->like($value);
                        } else {
                           $this->db->or_like($value);
                        }           
                        $this->db->group_end();
                        break;
                     case 'group_by':
                        $this->db->group_by($value);
                        break;
                     case 'limit':
                        if (!empty((int)$value)) {
                           if (isset($terms['page']) && !empty((int)$terms['page'])) {
                                 $offset = ((int)$terms['page'] - 1) * (int)$value;
                           } else {
                                 $offset = '';
                           }
                           $this->db->limit($value, $offset);
                        }
                        break;
                     case 'order_by':
                        if (isset($value['findex']) && $value['findex'] && isset($value['forder']) && $value['forder']) {
                           $this->db->order_by($value['findex'], $value['forder']);
                        }
                     break;
                     case 'keyword':
                        if (!empty($value) && isset($terms['field']) && !empty($terms['field'])) {
                           $this->db->group_start();
                           foreach ($terms['field'] as $field_key => $field) {
                                 $this->db->or_like($field, $value);
                           }
                           $this->db->group_end();
                        }
                        break;
               }
            }
         }
      }
   }

   /*
     * SQL CASE문 설정
     */
   public function _SELECT_CASE($select_case) {
      $result = '';
      if (!empty($select_case)) {
         foreach ($select_case as $column => $array) {
            $result .= ' ,( CASE ';
            foreach ($array as $key => $value) {
               $result .= ' WHEN '.$column.' = "'.$key.'" THEN "'.$value.'" ';
            }
            $result .= ' ELSE "" END) as '.$column.'_byname';
         }
      }
      return $result;
   }

   /*
     * 데이터 리스트, 카운트 반환
     */
   public function _GET_LIST_COUNT($terms = array(), $sop = "AND")
   {
      if (empty($this->table) || empty($this->primary_key)) {
         echo "CIV_MODEL _GET_LIST_COUNT ERROR";
         exit;
      }

      if (!isset($terms['order_by'])) {
         $terms['order_by']['findex'] = $this->primary_key;
         $terms['order_by']['forder'] = "desc";
      }
      $this->db->reset_query();
      $this->db->from($this->table);
      $this->_SET_TERMS($terms, $sop);
      $qry = $this->db->get();
      $result['list'] = $qry->result_array();

      $this->db->reset_query();
      $this->db->from($this->table);
      if (isset($terms['select'])) { unset($terms['select']); }
      if (isset($terms['limit'])) { unset($terms['limit']); }
      if (isset($terms['page'])) { unset($terms['page']); }
      $this->_SET_TERMS($terms, $sop);
      $this->db->select('count(*) as rownum');
      $qry = $this->db->get();
      $rows = $qry->row_array();
      $result['count'] = $rows['rownum'];

      if (!empty($result['list'])) {
         foreach ($result['list'] as $num => $row) {
            foreach ($row as $key => $value) {
               if (!empty($this->json_key) && in_array($key, $this->json_key) && !empty($value)) {
                     $result['list'][$num][$key] = json_decode($value, true);
               }
            }
         }
      }
      
      return $result;
   }

   /*
     * 데이터 행 반환
     */
   public function _GET_ROW($terms, $sop = "AND")
   {
      if (empty($this->table) || empty($this->primary_key)) {
         echo "CIV_MODEL _GET_ROW ERROR";
         exit;
      }

      $this->db->reset_query();
      $this->db->from($this->table);

      $this->_SET_TERMS($terms, $sop);

      $qry = $this->db->get();
      $result = $qry->row_array();
      if (!empty($result)) {
         foreach ($result as $key => $value) {
            if (!empty($this->json_key) && in_array($key, $this->json_key) && !empty($value)) {
               $result[$key] = json_decode($value, true);
            }
         }
      }
      return $result;
   }

   /*
   * 카운트 반환
   */
   public function _GET_COUNT($terms, $sop = "AND")
   {
      if (empty($this->table) || empty($this->primary_key)) {
         echo "CIV_MODEL _GET_COUNT ERROR";
         exit;
      }

      $this->db->reset_query();
      $this->db->from($this->table);
      $this->_SET_TERMS($terms, $sop);
      return $this->db->count_all_results();
   }

   /*
   * 설정 데이터 등록/수정
   */
   public function _METADATA_UPDATE($data)
   {
      if (empty($this->table) || empty($this->meta_name_key) || empty($this->meta_value_key)) {
         echo "CIV_MODEL _METADATA_UPDATE ERROR";
         exit;
      }

      $result = false;
      if ($data && is_array($data) && count($data) > 0) {
         $success_count = 0;
         foreach ($data as $name => $value) {
            //json_encode
            if (!empty($this->json_key) && in_array($name, $this->json_key) && !empty($value)) {
               $value = json_encode($value);
            }

            $this->db->reset_query();
            $this->db->from($this->table);
            $cnt = $this->db->where($this->meta_name_key, trim($name))->count_all_results();
            if ($cnt > 0) {
               $this->db->reset_query();
               $this->db->where($this->meta_name_key, trim($name));
               $rs = $this->db->update($this->table, array($this->meta_value_key => $value));
               if ($rs) {
                  $success_count++;
               }
            } else {
               $this->db->reset_query();
               $this->db->set(array($this->meta_name_key => $name, $this->meta_value_key => $value));
               $rs = $this->db->insert($this->table);
               if ($rs) {
                  $success_count++;
               }
            }
         }
         if ((int)count($data) === (int)$success_count) {
            $result = true;
         }
      }   
      return $result;
   }

   /*
   * 설정 데이터 반환
   */
   public function _GET_METADATA($key = '', $like = '')
   {
      if (empty($this->table) || empty($this->meta_name_key) || empty($this->meta_value_key)) {
         echo "CIV_MODEL _GET_METADATA ERROR";
         exit;
      }

      $this->db->reset_query();
      $this->db->from($this->table);

      if ($key && !empty($key)) {
         if (is_array($key)) {
            $key_where = array();
            foreach ($key as $i => $value) {
               $key_where[] = $this->meta_name_key.' = '.$value;
            }
            if (count($key_where) > 0) {
               $key_where = implode(' OR ', $key_where);
               $this->db->where($key_where, null, false);
            }
         } else {
            $this->db->where(array($this->meta_name_key => $key));
         }
      }

      if ($like) {
         $this->db->like(array($this->meta_name_key => $like));
      }

      $data = $this->db->get()->result_array();
      $result = array();
      if (!empty($data)) {
         foreach ($data as $i => $row) {
            //json_decode
            if (!empty($this->json_key) 
               && in_array($row[$this->meta_name_key], $this->json_key) 
               && !empty($row[$this->meta_value_key])) 
            {
               $row[$this->meta_value_key] = json_decode($row[$this->meta_value_key], true);
            }
            $result[$row[$this->meta_name_key]] = $row[$this->meta_value_key];
         }
      } 
      return $result;
   }
}
