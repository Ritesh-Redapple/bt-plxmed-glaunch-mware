<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db2 = $this->load->database('default', TRUE);
    }

    public function get_provider_params($client_id, $provider_id, $field_keys = array(), $account_type = 'SM', $game_category = '')
    {

        if (!empty($client_id)) {

            $query = "SELECT cpat.provider_account_id
            FROM provider_accounts pa
            INNER JOIN client_provider_account_tag cpat ON cpat.provider_account_id = pa.id
            WHERE cpat.client_id='{$client_id}' AND pa.account_type = '{$account_type}' 
            AND pa.provider_id = '{$provider_id}' 
            AND account_status = 'Active'";

            if ($game_category != "") {
                $query .= " AND pa.game_category='{$game_category}'";
            }
            $query .= " LIMIT 1";
            //echo $query;die;
            $run_query = $this->db2->query($query);
            $result_query = $run_query->row_array();

            if (!empty($result_query)) {

                $params_query = "SELECT * FROM provider_account_credentials 
                                 WHERE provider_account_id = '" . $result_query['provider_account_id'] . "'";
                if (!empty($field_keys)) {
                    $keyStr = '';
                    foreach ($field_keys as $val) {
                        $keyStr .= "'" . $val . "',";
                    }
                    $key_str = ($keyStr != '') ? substr($keyStr, 0, strlen($keyStr) - 1) : $keyStr;
                    $params_query .= " AND field_key IN (" . $key_str . ")";
                }

                $run_params_query = $this->db2->query($params_query);
                $result_params_query = $run_params_query->result_array();

                return $result_params_query;
            }
        }

        $default_query = "SELECT pac.* FROM provider_accounts pa
        INNER JOIN provider_account_credentials pac ON pac.provider_account_id = pa.id
        WHERE pa.provider_id = '{$provider_id}' AND pa.account_type = '{$account_type}' 
        AND pa.is_default = 'Y' AND pa.account_status = 'Active'";
        if ($game_category != "") {
            $default_query .= " AND pa.game_category='{$game_category}'";
        }

        if (!empty($field_keys)) {
            $keyStr = '';
            foreach ($field_keys as $val) {
                $keyStr .= "'" . $val . "',";
            }
            $key_str = ($keyStr != '') ? substr($keyStr, 0, strlen($keyStr) - 1) : $keyStr;
            $default_query .= " AND pac.field_key IN (" . $key_str . ")";
        }

        //echo $default_query;die;
        $run_default_query = $this->db2->query($default_query);
        $result_default_query = $run_default_query->result_array();

        return $result_default_query;
    }

    public function getUserDtlsByToken($field_key, $token, $provider_id,$client_id) 
    {
       
       $query= "SELECT usr.id, usr.reference_id, usr.username, usr.usercode, usr.first_name, usr.last_name, usr.mobile_no, usr.last_login, usr.available_balance, CT.currency_code AS currency, usr.status, usr.created_ts, cum.client_id, usr.last_played_provider, usr.account_type
                FROM client_users_provider_details_".$client_id." dtls 
                INNER JOIN client_users_".$client_id." usr
                    ON dtls.client_user_id = usr.id
                    AND usr.user_type = 'external'
                    AND usr.status = '0'
                INNER JOIN client_users_mapping cum ON cum.usercode = usr.usercode
                INNER JOIN clients AS CT ON CT.id = cum.client_id
                WHERE  dtls.field_key ='{$field_key}'
                    AND dtls.field_value ='{$token}'
                    AND dtls.provider_id ='{$provider_id}'
                ORDER BY dtls.id DESC";
           $result = $this->db2->query($query);
        return $result->row_array();
    }

    public function getGameDetailsbyCode($game_code, $provider_id)
    {
        $query = "SELECT GM.*, PM.module_name, PM.module_slug
                FROM games AS GM
                    LEFT JOIN providers_module AS PM
                        ON GM.module_id = PM.id
                    WHERE GM.game_code = '{$game_code}'
                    AND GM.provider_id = '{$provider_id}'
                    AND GM.status ='0'";
        $this->db2->query($query);
        $result = $this->db2->query($query);
        return $result->row_array();
    }
}
