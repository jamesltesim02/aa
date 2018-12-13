<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;
use \Think\UploadedFile;
// use maxiaojun\UploadFile;


/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class GameController extends AdminController {

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){

        $user = session('user_auth');

        $GameModel = M('game_info');

        $show_count = 15;

        if($_GET["default"])
        {
            $show_count = $_GET["default"];
        }

        if($_GET["starttime"])
        {
            $starttime = strtotime($_GET["starttime"]);
            if($_GET["endtime"])
            {
                $endtime = strtotime($_GET["endtime"]);
                $map["end_time"] =  array("BETWEEN", $starttime.','.$endtime);
            }
            else
            {
                $map['end_time'] = array("EGT", $starttime);
            }
            
        }
        else if($_GET["endtime"])
        {
            $endtime = strtotime($_GET["endtime"]);
            $map["end_time"] = array("ELT", $endtime);
        }


        if ($_GET["username"]) {
            $map['game_name'] = array("LIKE", '%' . $_GET["username"] . '%');
        }


        $page = new \Think\Page($GameModel->where($map)->count(), $show_count);

        $list = $GameModel->where($map)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();

        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');  //分页显示风格

        $count = 0;
        foreach ($list as $value) {
            $list[$count]["game_name"] = userTextDecode($value["game_name"]);
            $list[$count]["en_game_name"] = str_replace("/", "holemstr", $list[$count]["game_name"]);
            $count ++ ;
        }

        $Backend_Model = M('backend_member');
        $LoginUser = array();
        $LoginUser['username'] = $user['username'];
        $LoginUserLevel = $Backend_Model->where($LoginUser)->find();
        $Backendlevel_Model = M('backend_auth_level');
        $pass_map["level_title"] = "超級管理者";
        $pass_level = $Backendlevel_Model->where($pass_map)->find();
        $service_map["level_title"] = "客服";
        $service_level = $Backendlevel_Model->where($service_map)->find();


        $this->assign("service_level", $service_level['level_int']);
        $this->assign("pass_level", $pass_level['level_int']);
        $this->assign("LoginUserLevel", $LoginUserLevel['level']);
        $this->assign("_page", $page->show());
        $this->assign("starttime", $_GET["starttime"]);
        $this->assign("endtime", $_GET["endtime"]);
        $this->assign("select_username", $_GET["username"]);
        $this->assign('default_item', $show_count);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    public function del_game($game, $end_time)
    {
        $game_detail_Model = M("game_detail_log");
        $game_info_Model = M("game_info");
        $urlencode_game = str_replace("holemstr", "/", $game);

        $del_map["game_name"] = userTextEncode($urlencode_game);
        $del_map["end_time"] = $end_time;

        $game_detail_Model->where($del_map)->delete();
        $game_info_Model->where($del_map)->delete();

        $this->success("刪除成功", U("Game/index"));
    }

    public function game_user_inquiries()
    {
        $user = session('user_auth');

        $show_count = 15;

        if($_GET["default"])
        {
            $show_count = $_GET["default"];
        }

        if($_GET["starttime"])
        {
            $starttime = strtotime($_GET["starttime"]);
            if($_GET["endtime"])
            {
                $endtime = strtotime($_GET["endtime"]);
                $map["end_time"] =  array("BETWEEN", $starttime.','.$endtime);
            }
            else
            {
                $map['end_time'] = array("EGT", $starttime);
            }
            
        }
        else if($_GET["endtime"])
        {
            $endtime = strtotime($_GET["endtime"]);
            $map["end_time"] = array("ELT", $endtime);
        }

        if($_GET["guid"])
        {
            $map["game_userid"] = $_GET["guid"];
        }
        else if($_GET["username"])
        {    
            $play_map["frontend_user_auth"] = $_GET["username"];
            $UID_Model = M("ucenter_vid_member");

            $UID_list = $UID_Model->where($play_map)->field("game_vid")->select();
            

            if($UID_list != NULL)
            {
                $map["game_userid"] = array();
                $count = 0;

                foreach ($UID_list as $value) {
                    array_push($map["game_userid"], array('like', $value["game_vid"]));
                    $count++ ;
                }

                if($count != 1)
                {
                    array_push($map["game_userid"], "or");
                }
            }

        }

        if($map != NULL)
        {
            $GameModel = M('game_detail_log');

            $page = new \Think\Page($GameModel->where($map)->count(), (int)$show_count);
            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

            $list = $GameModel->where($map)->field("game_name,game_userid,game_username,end_time,end_earning_cash")->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select(); 

            $count = 0;
            foreach ($list as $value) {
                $list[$count]["game_name"] = userTextDecode($value["game_name"]);
                if($list[$count]["end_earning_cash"] > 0)
                {
                    $list[$count]["after_back_end"] = floor($list[$count]["end_earning_cash"] * 0.95);
                }
                else
                {
                    $list[$count]["after_back_end"] = $list[$count]["end_earning_cash"];
                }
                $count ++ ;
            }

            $this->assign("_page", $page->show()); 
        }

        $this->assign("starttime", $_GET["starttime"]);
        $this->assign("endtime", $_GET["endtime"]);
        $this->assign("username", $_GET["username"]);
        $this->assign("guid", $_GET["guid"]);
        $this->assign('default_item' , $show_count);
        $this->assign('list', $list);
        $this->display();
    }

    public function during_billing_history()
    {
        $game_detail_logModel = M('game_detail_log');
        $game_info_Model = M('game_info');

        $list_select_map = array();
        $map = array();

        if($_GET["starttime"])
        {
            $starttime = strtotime($_GET["starttime"]);
            if($_GET["endtime"])
            {
                $endtime = strtotime($_GET["endtime"]);
                $map["end_time"] =  array("BETWEEN", $starttime.','.$endtime);
                $list_select_map["end_time"] = array("BETWEEN", $starttime.','.$endtime);
            }
            else
            {
                $map['end_time'] = array("EGT", $starttime);
                $list_select_map["end_time"] = array("EGT", $starttime);
            }
            
        }
        else if($_GET["endtime"])
        {
            $endtime = strtotime($_GET["endtime"]);
            $map["end_time"] = array("ELT", $endtime);
            $list_select_map["end_time"] = array("ELT", $endtime);
        }


        $club_game_time = $game_info_Model->where($map)->count();
        $club_list = $game_detail_logModel->where($map)->group('club_id')->field('club_id,club_name,count(*) as times')->select();
        $game_type_list = $game_detail_logModel->where($map)->group('game_type,game_blind')->field('game_type,game_blind')->select();


        if($club_list != null && $game_type_list != null && ($_GET["starttime"] != null || $_GET["endtime"] != null))
        {
            $game_data_list = array();

            $count = 0;
            $total_end_num[0] = "输赢交接数";

            
            

            foreach($game_type_list as $value)
            {
                $list_select_map["game_type"] = $value["game_type"];
                $list_select_map["game_blind"] = $value["game_blind"];
                $get_game_data_list = $game_detail_logModel->where($list_select_map)->field('club_id,game_type,game_blind,end_earning_cash,safety_total')->select();
                $sum_game_data_list = array();

                $sum_count = 0;
                $flag = 0;
                foreach ($club_list as $club) 
                {
                    foreach ($get_game_data_list as $value)
                    {
                        if($club["club_id"] == $value["club_id"])
                        {
                            
                            $have_data_count = 0;
                            foreach ($sum_game_data_list as $list) 
                            {
                                if(in_array($club["club_id"], $list))
                                {
                                    $flag = 1;
                                    break;
                                }

                                $have_data_count++;
                            }


                            if($flag == 1)
                            {
                                $buff = $sum_count;
                                $sum_count = $have_data_count;
                            }
                                
                            if($value["end_earning_cash"] > 0)
                            {
                                $sum_game_data_list[$sum_count]["end_earning_cash"] +=  floor($value["end_earning_cash"]) - floor(round(abs($value["end_earning_cash"]) * 0.025));
                            }
                            else
                            {
                                $sum_game_data_list[$sum_count]["end_earning_cash"] +=  floor($value["end_earning_cash"]) + floor(round(abs($value["end_earning_cash"]) * 0.025));
                            }

                            if($value["safety_total"] > 0)
                            {
                                $sum_game_data_list[$sum_count]["safety_total"] -= abs(floor(round($value["safety_total"] * 0.975)));
                            }
                            else
                            {
                                $sum_game_data_list[$sum_count]["safety_total"] += abs(floor(round($value["safety_total"] * 0.975)));
                            }

                            $sum_game_data_list[$sum_count]["back"] += abs(floor(round(abs($value["end_earning_cash"]) * 0.025)));
                            $sum_game_data_list[$sum_count]["club_id"] = $value["club_id"];
                            $sum_game_data_list[$sum_count]["game_type"] = $value["game_type"];
                            $sum_game_data_list[$sum_count]["game_blind"] = $value["game_blind"];

                            // if($value["game_type"] == "奥马哈保险局" && $value["game_blind"] == "2/4" && $value["club_id"] == "20270702")
                            //     dump($sum_game_data_list);

                            if($flag == 1)
                            {
                                $sum_count = $buff;
                            }
                            else
                            {
                                $sum_count++;
                            }
                        
                        }
                          
                    }

                }

                
                $game_data_list[$count+1]["0"] = $game_type_list[$count]["game_type"] . "(" . $game_type_list[$count]["game_blind"] . ")";
                $game_back_list[$count]["0"] = $game_type_list[$count]["game_type"] . "(" . $game_type_list[$count]["game_blind"] . ")";

                $game_safety_list[$count]["0"] = $game_type_list[$count]["game_type"] . "(" . $game_type_list[$count]["game_blind"] . ")";

                $total_data = "0";
                $total_back = "0";
                $total_safety = "0";
                $check_data_list =array();
                $check_back_list =array();
                $check_safety_list =array();
                $check_data_list[$count] = 1;
                $check_back_list[$count] = 1;
                $check_safety_list[$count] = 1;
                foreach ($sum_game_data_list as $key) {
                    $club_count = 0;
                    foreach ($club_list as $value) {
                        if($key["club_id"] == $value["club_id"])
                        {

                            //back
                            $game_back_list[$count][$club_count+1] =  $key["back"];
                            $total_back_num[$club_count] += $key["back"];
                            $sum_total_back_num += $key["back"];
                            //sum back value
                            $total_back +=$game_back_list[$count][$club_count+1];
                            if (floor($key["end_earning_cash"] * 0.05) > 0)
                            {
                                $check_back_list[$count] = 0;  
                            }
                            
                            //safety_total
                            $game_safety_list[$count][$club_count+1] = $key["safety_total"];
                            $total_safety_num[$club_count] += $key["safety_total"];
                            $sum_total_safety_num += $key["safety_total"];
                            //sum safety total value
                            $total_safety += $game_safety_list[$count][$club_count+1];
                            if($key["safety_total"] > 0)
                            {
                                $check_safety_list[$count] = 0;
                            }

                            //game end 
                            $game_data_list[$count+1][$club_count+1] =  $key["end_earning_cash"];
                            $total_end_num[$club_count+1] += $key["end_earning_cash"];
                            $sum_total_end_num += $key["end_earning_cash"];

                            //sum total value
                            $total_data += $game_data_list[$count+1][$club_count+1];
                            $check_data_list[$count] = 0;
                            
                        }
                        else 
                        {
                            if($game_data_list[$count+1][$club_count+1] == NULL)
                            {
                                //game end
                                $game_data_list[$count+1][$club_count+1] = 0;
                                //game back
                                $game_back_list[$count][$club_count+1] = 0;
                                //safety_total
                                $game_safety_list[$count][$club_count+1] = 0;
                            }
                            
                        }

                        //all club set value
                        $club_count ++;
                    }

                    
                }

                array_push($game_back_list[$count], $total_back);
                array_push($game_data_list[$count+1], $total_data);
                array_push($game_safety_list[$count], $total_safety);

                if($check_back_list[$count] == 1)
                {
                   unset($game_back_list[$count]); 
                }


                if($check_data_list[$count] == 1)
                {
                   unset($game_data_list[$count+1]); 
                }

                

                if($check_safety_list[$count] == 1)
                {
                   unset($game_safety_list[$count]); 
                }

                $count++;
            }

            $total_user_times = 0;
            foreach ($club_list as  $value) {
                $total_user_times += $value["times"];
            }

            $safety_count = 0;
            $actual_handover_list = array();
            $actual_handover = 0;
            $Total_transfer_list = array();
            $Total_transfer = 0;
            foreach ($total_safety_num as  $value)
            {
                $Total_transfer_list[$safety_count] = $total_end_num[$safety_count+1];
                $Total_transferactual_handover_list = $value + $total_end_num[$safety_count+1];
                // 
                $Union_cash[$safety_count] = -$club_game_time *5;
                $Total_Union_cash =  -$club_game_time *5;
                $Total_transfer += $Total_transfer_list[$safety_count];
                $actual_handover += $actual_handover_list[$safety_count];
                $safety_count++;
            }


            foreach ($game_safety_list as  $game_safety)
            {
                $first_is_title_flag = 0;
                $safety_count = 0;
                foreach ($game_safety as $value) {
                    
                    if($first_is_title_flag == 0)
                    {
                        $first_is_title_flag = 1;
                        continue;
                    }
                    else if($safety_count == (count($game_safety)-2))
                    //-2 is title and total 
                    {
                        break;
                    }
                    $Total_transfer_list[$safety_count] += $value;
                    $Total_transfer += $value;
                    $safety_count++;
                }
            }

            array_push($Total_transfer_list, $Total_transfer);

            $safety_count = 0;
            foreach ($Total_transfer_list as $value) {
                $actual_handover_list[$safety_count] = $value - ($club_game_time *5);
                $actual_handover += $value - ($club_game_time *5);
                $safety_count ++;
            }
            
            // array_push($actual_handover_list, $actual_handover);
            array_push($Union_cash, $Total_Union_cash);

            //sort by key
            ksort($total_end_num);
            ksort($total_back_num);
            ksort($total_safety_num);

            $game_data_list[0] = $total_end_num;

            ksort($game_data_list);
            ksort($game_back_list);

            array_push($game_data_list[0], $sum_total_end_num);
            array_push($total_back_num, $sum_total_back_num);
            array_push($total_safety_num, $sum_total_safety_num);

            $count = 0;
            foreach ($club_list as  $value) {
                $club_list_list[$count]["club_id"] = $value["club_id"];
                $club_list_list[$count]["club_name"] = userTextDecode($value["club_name"]);
                $club_list_list[$count]["times"] = $value["times"];
                $count ++;
            }

            // exit();

            $this->assign('have_data', 1);
            $this->assign("starttime", $_GET["starttime"]);
            $this->assign("endtime", $_GET["endtime"]);
            $this->assign('club_list', $club_list_list);
            $this->assign('Union_cash', $Union_cash);
            $this->assign('game_type_list', $game_type_list);
            $this->assign('game_data_list', $game_data_list);
            $this->assign('game_back_list', $game_back_list);
            $this->assign('total_back_num', $total_back_num);
            $this->assign('total_safety_num', $total_safety_num);
            $this->assign('game_safety_list', $game_safety_list);
            $this->assign('total_user_times', $total_user_times);
            $this->assign('Total_transfer_list', $Total_transfer_list);
            $this->assign('actual_handover_list', $actual_handover_list);
            $this->display();
        }
        else
        {
            // $this->error('时间内没有任何资料'); 
            $this->assign('have_data', 0);
            $this->display(); 
        }

    }

    public function user_detail($game){

        $user = session('user_auth');
        $urlencode_game = str_replace("holemstr", "/", $game);
        $GameModel = M('game_detail_log');
        
        $map['game_name'] = userTextEncode($urlencode_game);

        if (I("userName")) {
            $map['game_userid'] = array("LIKE", '%' . htmlspecialchars(I("userName")) . '%');
        }

        $list = $GameModel->where($map)->select();

        $count = 0;
        foreach ($list as $value) {
            $list[$count]["game_name"] = userTextDecode($value["game_name"]);
            $list[$count]["game_username"] = userTextDecode($value["game_username"]);
            $list[$count]["club_name"] = userTextDecode($value["club_name"]);
            $count ++ ;
        }

        // $page = new \Think\Page($GameModel->where($map)->count(), 20);

        // $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');  //分页显示风格

        // $this->assign("_page", $page->show());
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    public function club_detail($game){

        $user = session('user_auth');

        $GameModel = M('game_detail_log');
        $urlencode_game = str_replace("holemstr", "/", $game);
        $map['game_name'] = userTextEncode($urlencode_game);
        $total_map["game_name"] = userTextEncode($urlencode_game);
        $total_map["end_earning_cash"] = array('egt' , 0);

        $clublist = $GameModel->where($map)->group('club_id')->field('club_id,club_name,SUM(end_earning_cash) as end_earning_cash,SUM(safety_total) as safety_total')->select();

        // $total_list_data = $GameModel->where($total_map)->group('club_id')->field('club_id,SUM(end_earning_cash) as end_earning_cash,SUM(safety_total) as safety_total')->select();

        $all_list = $GameModel->where($map)->select();

        $show_list = array();
        $total_list = array();
        $check_have_user_end_earning_cash_tag = array();

        $count = 0;

        foreach ($all_list as $list) {
            if($list["end_earning_cash"] < 0)
            {
                $show_list[$count]["user_end_earning_cash"] = 0;
                $show_list[$count]["back_end_earning_cash"] = 0;
                
            }
            else
            {
                $check_have_user_end_earning_cash_tag[$list["club_id"]] = 1; 
                $show_list[$count]["user_end_earning_cash"] = floor(round($list["end_earning_cash"] * 0.95));
                $show_list[$count]["back_end_earning_cash"] = floor(round($list["end_earning_cash"] * 0.05));
                 
            }
            $show_list[$count]["end_unbot"] = abs(floor(round(abs($list["end_earning_cash"]) * 0.025))); 
            $show_list[$count]["safety_total_end"] = abs(floor(round($list["safety_total"] * 0.975)));
            $show_list[$count]["game_username"] = $list["game_username"];
            $show_list[$count]["game_userid"] = $list["game_userid"];
            $show_list[$count]["club_id"] = $list["club_id"];
            $show_list[$count]["end_earning_cash"] = $list["end_earning_cash"];

            $count++;
        }

        // dump($show_list);
        // exit();

        $count = 0;
        foreach ($clublist as $club) {
            
            foreach ($show_list as $list_data)
            {
                if($club["club_id"] == $list_data["club_id"])
                {
                    
                    $total_list[$count]["safety_total_end"] += $list_data["safety_total_end"];
                    $total_list[$count]["user_end_earning_cash"] += $list_data["user_end_earning_cash"];
                    $total_list[$count]["back_end_earning_cash"] += $list_data["back_end_earning_cash"];
                    $total_list[$count]["end_unbot"] += $list_data["end_unbot"];
                    $total_list[$count]["end_earning_cash"] += $list_data["end_earning_cash"];
                    $total_list[$count]["club_id"] = $list_data["club_id"];
                }
            }

            $count++;

        }

        $count = 0;
        foreach ($clublist as $value) {
            $clublist[$count]["club_name"] = userTextDecode($value["club_name"]);
            $count ++ ;
        }

        $count = 0;
        foreach ($show_list as $value) {
            $show_list[$count]["game_username"] = userTextDecode($value["game_username"]);
            $count ++ ;
        }


        $this->assign('list', $show_list);
        $this->assign('total_list', $total_list);
        $this->assign('clublist', $clublist);
        $this->meta_title = '用户信息';
        $this->display();
    }




    public function upload2(){
        $files = $_FILES['exl']['name'];


        if(!strpos($files, ".xls")){
            $this->error('不是Excel(xls)文件，请重新上传');    
        }
        
        // 上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     'Download/'; // 设置附件上传（子）目录
        //$upload->subName   =     array('date', 'Ym');
        $upload->subName   =     '';
        // 上传文件  
        $info   =   $upload->upload();
    
        $file_name =  $upload->rootPath.$info['exl']['savepath'].$info['exl']['savename'];
        $exl = $this->import_exl($file_name);

        if($exl == -1){
            unlink($file_name);
            $this->error('匯入已有的賽局'); 
        }

        $count = count($exl);

        // 检测表格导入成功后，是否有数据生成
        if($count<1){
            unlink($file_name);
            $this->error('未检测到有效数据');    
        }

        // 实例化数据
        $this->assign('goods',$goods);
        //print_r($f);
        
        // 统计结果
        $total['count'] = $count;
        $total['success'] = $f;
        $total['error'] = $w;
        $this->assign('total',$total);
        
        // 删除Excel文件
        unlink($file_name);
        $this->success('匯入成功', U('Game/index'));
            
    }
    
    public function import_settlement_csv(){
        $files = $_FILES['exl']['name'];


        if(!strpos($files, ".xls")){
            $this->error('不是Excel(xls)文件，请重新上传');    
        }
        
        // 上传
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls','xlsx');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     'settlement/'; // 设置附件上传（子）目录
        //$upload->subName   =     array('date', 'Ym');
        $upload->subName   =     '';
        // 上传文件  
        $info   =   $upload->upload();
    
        if ($info) {
            $this->success('上传成功', U('Game/index'));
        } else {
            $this->error('上传失败');
        }    
    }
    
/* 处理上传exl数据
     * $file_name  文件路径
     */
    public function import_exl($file_name){
        import("Org.Util.PHPExcel");   // 这里不能漏掉
        import("Org.Util.PHPExcel.IOFactory");
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        // $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
        $objPHPExcel = $objReader->load($file_name);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数

        $game_log_Model = M("game_log");
        $player_purse_Model = M("player_purse");
        $ucenter_vid_member_Model = M("ucenter_vid_member");
        $game_info = M("game_info");
        
        for($i=1;$i<$highestRow+1;$i++){
            $db_title = array("game_type", "game_name", "create_club", "game_blind", "game_table", "game_total_time", 
                        "game_total_Action", "game_userid", "game_username", "club_id", "club_name", "init_cash", 
                        "end_cash", "safety_buy_cash", "safety_earning_cash", "safety_total", "safety_club", 
                        "safety", "end_earning_cash", "end_time");
            $excel_x = range('A', $highestColumn);

            for($count = 0; $count < 20; $count++)
            {
                if($db_title[$count] == "game_userid")
                {
                    //get vid in username
                    $vid_member_map["game_vid"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                    $vid_member_data = $ucenter_vid_member_Model->where($vid_member_map)->select();
                    $select_player_purse_map["userid"] = $vid_member_data[0]["frontend_user_id"];
                    $select_player_purse_map["username"] = $vid_member_data[0]["frontend_user_auth"];
                    $change_data["userid"] = $vid_member_data[0]["frontend_user_id"]; 
                    $change_data["username"] = $vid_member_data[0]["frontend_user_auth"]; 

                    //for test:start
                    // $select_player_purse_map["userid"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                    
                    // $change_data["userid"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                    //for test:end

                }
                //for test:start
                // elseif ($db_title[$count] == "game_username") {
                //     $change_data["username"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                //     $select_player_purse_map["username"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                // }
                //for test: end
                else if ($db_title[$count] == "end_cash") {
                    //get cash(*0.9)  on end game
                    $change_data["cash"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                }
                else if ($db_title[$count] == "game_type") {
                    $add_game_info_data["game_type"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                }
                else if ($db_title[$count] == "game_name") {
                    $str = mb_convert_encoding($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue(), "UTF-8", "auto");
                    $add_game_info_data["game_name"] = userTextEncode($str);
                }
                else if ($db_title[$count] == "safety_total") {
                    $safety_numb = $safety_numb + $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                }
                else if ($db_title[$count] == "init_cash") {
                    $change_data["init_cash"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                }
                else if ($db_title[$count] == "end_earning_cash") {
                    $change_data["cash"] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();

                    $add_game_info_data["game_point"] = $add_game_info_data["game_point"] + $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();

                    if($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue() > 0)
                    {
                        $win_numb = $win_numb + $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                    }
                    else
                    {
                        $lose_numb = $lose_numb + $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                    }

                }

                
                if($db_title[$count] == "end_time")
                {
                    $data[$i][$db_title[$count]] =  strtotime($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue());

                    $add_game_info_data["end_time"] = strtotime($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue());
                }  
                else if($db_title[$count] == "create_club" || $db_title[$count] =="club_name")
                {
                    $data[$i][$db_title[$count]] = userTextEncode($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue());
                }
                else if($db_title[$count] =="game_name" || $db_title[$count] == "game_username")
                {
                    $str = mb_convert_encoding($objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue(), "UTF-8", "auto");
                    $data[$i][$db_title[$count]] = userTextEncode($str);
                }
                else
                {
                    $data[$i][$db_title[$count]] = $objPHPExcel->getActiveSheet()->getCell($excel_x[$count].$i)->getValue();
                }  
 
            }
                        
            if($i != 1)
            {
                
                $check_have_data_map["game_name"] = $add_game_info_data["game_name"];

                if($game_info->where($check_have_data_map)->count() != 0)
                {
                    // $null_array = array();
                    return -1;
                }

                $player_purse_data = $player_purse_Model->where($select_player_purse_map)->Field('cash')->find();
                $cash_back_value = D("cash_back")->Field('cash_back')->find();
                $change_value = floor($change_data["init_cash"] + $change_data["cash"]*$cash_back_value["cash_back"]);
                if($change_value > 0)
                {
                    if($change_data["cash"] > 0 )
                    {
                        $change_data["cash"] = floor($player_purse_data["cash"] + $change_data["init_cash"] + $change_data["cash"]*$cash_back_value["cash_back"]);
                    }
                    else
                    {
                       $change_data["cash"] = floor($player_purse_data["cash"] + $change_data["init_cash"] + $change_data["cash"]); 
                    }

                    $player_purse_status = $player_purse_Model->where($select_player_purse_map)->save($change_data);
                }
                //for test
                // $player_purse_Model->add($change_data);
                $game_detail_log = M("game_detail_log");

                $game_detail_log->add($data[$i]);  
                 
            }
        }
        //add gme info
        $add_game_info_data["game_user_settlement_amount"] = floor($win_numb *0.95);
        $add_game_info_data["game_user_back"] = floor($win_numb *0.05);
        $add_game_info_data["game_end_settlement_amount"] = $win_numb + floor($lose_numb *0.025);
        $add_game_info_data["game_safety"] = $safety_numb;
        $add_game_info_data["game_end_safety"] = -floor($safety_numb * 0.975);
        $add_game_info_data["testname"] = $add_game_info_data["game_name"];
        
        $game_info->add($add_game_info_data);

        return $data;    
    }

    public function isEmojiCharacter($codePoint) {
        return !(($codePoint == 0x0) ||
                ($codePoint == 0x9) ||
                ($codePoint == 0xA) ||
                ($codePoint == 0xD) ||
                (($codePoint >= 0x20) && ($codePoint <= 0xD7FF)) ||
                (($codePoint >= 0xE000) && ($codePoint <= 0xFFFD)) ||
                (($codePoint >= 0x10000) && ($codePoint <= 0x10FFFF)));
    }

    public function join_game()
    {
        if(IS_POST)
        {   
            $user = session('user_auth');
            $user_purse_Model = M("player_purse");
            $join_game_log_Model = M("join_game_log");
            $UID_model = M("ucenter_vid_member");

            $get_username_map["game_vid"] = $_POST["uid"];
            $get_username_data = $UID_model->where($get_username_map)->find();

            $check_purse_map = array();
            $check_purse_map["username"] =  $get_username_data["frontend_user_auth"];

            $get_user_id_mapp["username"] =  $get_username_data["frontend_user_auth"];

            //get cash info
            $cash = $user_purse_Model->where($check_purse_map)->Field('id,cash')->find();

            //check purse cash is ok 
            if($cash["cash"] < $_POST["cash"])
            {
                $userid = $user_purse_Model->where($get_user_id_mapp)->Field('id')->find();
                $add_log_data["userid"] = $userid["id"];
                $add_log_data["username"] = $get_username_data["frontend_user_auth"];
                $add_log_data["game_vid"] = $_POST["uid"];
                $add_log_data["club_id"] = $_POST["clubid"];
                $add_log_data["join_cash"] = $_POST["cash"];
                // $add_log_data["room_name"] = $_POST["room_name"];
                $add_log_data["application_time"] = time();
                $add_log_data["check_time"] = time();
                $add_log_data["check_user"] = $user['username'];
                $add_log_data["check_status"] = "不通過!金幣不夠上分";

                $join_game_log_Model->add($add_log_data);

                $this->error('金幣不夠上分', U('Game/join_game'));
            }
            else
            {
                //Chargeback
                $update_cash["cash"] = $cash["cash"] - $_POST["cash"];
                $update_status = $user_purse_Model->where($check_purse_map)->save($update_cash);

                //write ready add log data 
                $userid = $user_purse_Model->where($get_user_id_mapp)->Field('id')->find();
                $add_log_data["userid"] = $userid["id"];
                $add_log_data["username"] = $get_username_data["frontend_user_auth"];
                $add_log_data["game_vid"] = $_POST["uid"];
                $add_log_data["club_id"] = $_POST["clubid"];
                $add_log_data["join_cash"] = $_POST["cash"];
                $add_log_data["room_name"] = $_POST["room_name"];
                $add_log_data["application_time"] = time();
                $add_log_data["check_time"] = time();
                $add_log_data["check_user"] = $user['username'];

                if(!$update_status)
                {
                    $add_log_data["check_status"] = "不通過!金幣不夠上分";

                    $join_game_log_Model->add($add_log_data);

                    $this->error('金幣不夠上分', U('Game/join_game'));
                }
                else
                {
                    
                    $add_log_data["check_status"] = "通過!";

                    $join_game_log_Model->add($add_log_data);

                    $this->success('匯入成功', U('Game/join_game'));
                }
            }
            
        }
        else
        {
            $this->display();
        }
    }

    public function join_game_log()
    {
        $user = session('user_auth');

        $join_game_Model = M("join_game_log");

        if($_GET["uname"])
        {
            $map["username"] = $_GET["uname"];
        }

        if($_GET["uid"])
        {
            $map["game_vid"] = $_GET["uid"];
        }

        $page = new \Think\Page($join_game_Model->where($map)->count(), 20);


        $list = $join_game_Model->where($map)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();

        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');  //分页显示风格

        $this->assign("_page", $page->show());

        $this->assign("_list", $list);
        $this->assign("select_uid", $_GET["uid"]);
        $this->assign("select_username", $_GET["uname"]);

        $this->display();
    }

    

}
