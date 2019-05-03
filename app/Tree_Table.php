<?php

namespace App;

use DB;
use Html;
use Auth;
use Crypt;
use Storage;

use Illuminate\Database\Eloquent\Model;

class Tree_Table extends Model
{
    public static $downline_users = '';

    public static $upline_users   = array();
    public static $upline_id_list = array();

    public static $MODEL_NOT_FOUND = '-1';

    protected $table = 'tree_table';

    protected $fillable = ['user_id', 'sponsor', 'placement_id', 'leg'];

    public static function getmaxid()
    {
        $users = DB::table('users')->max('user_id');
        return $users;
    }
    public static function getSponsorName($user_id)
    {
        return $sponsor_id = DB::table('tree_table')->where('user_id', $user_id)->value('sponsor');
    }

    public static function getPlacementId($sponsor_id, $leg)
    {
        $user_id = self::where('placement_id', $sponsor_id)->where("leg", $leg)->where("type", "<>", "vaccant")->value('user_id');

        if (!$user_id) {
            return $sponsor_id;
        }

        return self::getPlacementId($user_id, $leg);

    }
    public static function vaccantId($placement_id, $leg)
    {

        $data = self::where('placement_id', $placement_id)->where("leg", $leg)->where("type", "=", "vaccant")->value('id');

        return $data;

    }

    public static function createVaccant($placement_id)
    {

        Tree_Table::create([
            'sponsor'      => 0,
            'user_id'      => '0',
            'placement_id' => $placement_id,
            'leg'          => 'L',
            'type'         => 'vaccant',
        ]);

        Tree_Table::create([
            'sponsor'      => 0,
            'user_id'      => '0',
            'placement_id' => $placement_id,
            'leg'          => 'R',
            'type'         => 'vaccant',
        ]);

    }
    public static function takeUserId($user_name)
    {
        return DB::table('users')->where('username', $user_name)->value('id');
    }


    public static function getTree($root = true, $placement_id = "", $treedata = array(), $level = 0, $levellimit)
    {


        if ($level == $levellimit) {
            return false;
        } 
        if ($root) {
            $data = self::where('tree_table.user_id', $placement_id)
                ->leftJoin('users', 'tree_table.user_id', '=', 'users.id')
                ->leftJoin('profile_infos', 'profile_infos.user_id', '=', 'tree_table.user_id')
                ->leftJoin('rank_setting', 'rank_setting.id', '=', 'users.rank_id')
                ->leftJoin('point_table', 'point_table.user_id', '=', 'tree_table.user_id')
                ->select('tree_table.*', 'users.username', 'profile_infos.image','profile_infos.package', 'point_table.left_carry', 'point_table.right_carry', 'point_table.total_left', 'point_table.total_right', 'rank_setting.rank_name','users.name','users.created_at','users.email')
                ->get();
        } else {
            $data = self::where('placement_id', $placement_id)->orderBy('leg', 'ASC')
                ->leftJoin('users', 'tree_table.user_id', '=', 'users.id')
                ->leftJoin('profile_infos', 'profile_infos.user_id', '=', 'tree_table.user_id')
                ->leftJoin('rank_setting', 'rank_setting.id', '=', 'users.rank_id')
                ->leftJoin('point_table', 'point_table.user_id', '=', 'tree_table.user_id')
                ->select('tree_table.*', 'users.username', 'profile_infos.image','profile_infos.package', 'point_table.left_carry', 'point_table.right_carry', 'point_table.total_left', 'point_table.total_right', 'rank_setting.rank_name','users.name','users.created_at','users.email')
                ->get();
        }

        $currentuserid = Auth::user()->id;
        $treearray = [];
        // print_r($data);
        foreach ($data as $key => $value) {
            if ($value->type == "yes" || $value->type == "no") {
                if ($root) {
                    $push = self::getTree(false, $value->user_id, $treearray, $level + 1,$levellimit);
                    $class = 'up';
                    $usertype = 'root';
                    $user_active_class = 'active';
                } else {
                    $push = self::getTree(false, $value->user_id, $treearray, $level + 1,$levellimit);
                    $class='down';
                    $usertype = 'child';

                    if($value['type']=='yes'){                        
                        $user_active_class = 'active';
                    }
                    elseif($value['type']=='no'){
                        $user_active_class = 'inactive';
                    }

                }

                $username         = $value->username;
                $id               = $value->user_id;
                $accessid         = Crypt::encrypt($value->user_id);

                // $package_id   = Profileinfo::where('user_id', $value->user_id)->value('package');

                   $package_name = Packages::where('id','=',$value->package)->value('package');
                // echo "  ---  $value->username   --- $value->package  ,   </br>";
                // $content = '' . Html::image('http://randomuser.me/api/portraits/men/'.$imgname.'.jpg', $username, array('class'=>$class.' tree-user','style' => 'max-width:50px;cursor:pointer;','data-accessid'=>$accessid)) . '';
                $content = '' . Html::image(route('imagecache', ['template' => 'profile', 'filename' => self::profilePhoto($username)]), $username, array('class'=>$class.' tree-user','style' => 'max-width:50px;','data-accessid'=>$accessid)) . '';
                // $content = 'aa';

                $coverPhoto = '' . Html::image(route('imagecache', ['template' => 'large', 'filename' => self::coverPhoto($username)]), $username, array('class'=>$class.' tree-user','style' => '','data-accessid'=>$accessid)) . '';

                

                $info    = "
                <div class='hoverouter'>
                <div class='hoverinner'>
                    
                            <div class='coverholder'>
                                $coverPhoto
                            </div>
                            <div class='backgroundgd'>
                            </div>
                        
                    <div class='primeinfo' >
                        <div class='primeinfohold' >
                            
                                <div class='ellipsis username'>
                                    $value->username
                                </div>
                           
                        </div>
                        <ul class='secondaryinfo'>
                            <li>
                                <span class='key'>Name</span> : <span class='value'>$value->name</span>
                            </li> 
                            <li>
                                <span class='key'>Email </span> : <span class='value'>$value->email</span>
                            </li>
                            <li>
                                <span class='key'>Joining date</span> : <span class='value'>$value->created_at</span>
                            </li> 
                            <li class='rankname'>
                                <span class='key'>Rank</span> :  <span class='value'>$value->rank_name</span>
                            </li class='packagename'>                            
                            <li>
                                <span class='key'>Package</span> : <span class='value'>$package_name</span>
                            </li>  
                            <li>
                            <br/>
                            </li>        

                            <!-- 
                            <li class='topupcount'>
                                <span class='key'>Top Ups</span> : <span class='value'>".PurchaseHistory::where('user_id', '=', $value->user_id)->sum('count')."</span>
                            </li>                            
                            <li class='rsbalance'>
                                <span class='key'>RS balance</span> : <span class='value'>$value->revenue_share</span>
                            </li> 
                            -->
                        </ul>
                    </div>
                </div>
                <table cellpadding='0' cellspacing='0' class='profcontenttbl' >
                    <tbody>
                        <tr>
                            <td rowspan='2' valign='top'>
                               
                                    <div class='profpicholder'>
                                        $content
                                    </div>
                              
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                <div class='pillforholder'>
                </div>
                <div class='details'>
             
                
                <table class='table table-condensed'>
                <thead>
                <tr>
                    <td>Total left</td>
                    <td>Total right</td>
                    <td>Left point</td>
                    <td>Right point</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>$value->total_left</td>
                    <td>$value->total_right</td>
                    <td>$value->left_carry</td>
                    <td>$value->right_carry</td>
                </tr>
                </tbody>
                </table>
      
                </div>
                </div>";
                $className = $user_active_class;
                $treearray[$value->id]['name']      = $username;
                $treearray[$value->id]['content']   = $content;
                $treearray[$value->id]['accessid']      =  $accessid;
                $treearray[$value->id]['id']      =  $id;
                $treearray[$value->id]['currentuserid'] = $currentuserid;
                $treearray[$value->id]['info']      = $info;
                $treearray[$value->id]['className'] = $className;
                $treearray[$value->id]['usertype'] = $usertype;
                if (!empty(array_first($push)) || !empty(array_last($push))) {
                    $treearray[$value->id]['children'] = [array_first($push), array_last($push)];
                }

            } else {
                // $placement_username = User::where('id',$placement_id)->value('username');
                // dd($value->placement_id);
                $placement_accessid = Crypt::encrypt(Tree_Table::where('placement_id',$value->placement_id)->value('id'));
                $placement_accessid = Crypt::encrypt($value->id);
                // dd(urldecode(Crypt::decrypt($placement_accessid)));

                $username      = "<span class='enroll'>Add here</span>";                
                $content   = "<img class='' data-accessid='$placement_accessid' style='max-width:50px;cursor:pointer;' src='/files/images/users/profile_photos/thumbs/plus.png'>";
                $info      = "";
                $className = "vacant";
                $treearray[$value->id]['name']      = $username;
                $treearray[$value->id]['content']   = $content;
                $treearray[$value->id]['info']      = $info;
                $treearray[$value->id]['className'] = $className;
                $treearray[$value->id]['usertype'] = 'vacant';
                $treearray[$value->id]['placement_accessid'] = $placement_accessid;
                // $treearray[$value->id]['placement_username'] = $placement_username;
            }
        }
        $treedata = $treearray;
        return $treedata;
    }


    public static function profilePhoto($user_name)
    {
        $user  = User::where('username', $user_name)->with('profile_info')->first();
        $image = $user->profile_info->profile;
        //if (!Storage::disk('images')->exists($image)){
        //    $image = 'avatar-big.png';
        //}
        if(!$image){
            $image = 'avatar-big.png';
        }

        return $image;
    }

    public static function coverPhoto($user_name)
    {
        $user  = User::where('username', $user_name)->with('profile_info')->first();
        $image = $user->profile_info->cover;
        if (!Storage::disk('images')->exists($image)){
            $image = 'cover.jpg';
        }
        if(!$image){
            $image = 'cover.jpg';
        }
        return $image;
    }


    public static function generateTree($users, $level = 0, $tree_structure = "")
    {
        $x = collect(collect($users)->first());
        return $x->toJson();
        
        
    }

    public static function getMyReferals($sponsor_id)
    {
        $users      = DB::table('tree_table')->where('sponsor', $sponsor_id)->get();
        $index      = 0;
        $user_array = array();
        foreach ($users as $user) {
            $user_array[$index] = Self::getUserDetails($user->user_id);
            $index++;
        }
        return $user_array;
    }

    public static function getUserDetails($user_id)
    {
        return DB::table('users')->where('id', $user_id)->get();
    }
    public static function getDownlines($index, $root = true, $placement_id = "", $downline_user, $level = 0, $count = 0)
    {
        $data  = self::where('sponsor', $placement_id)->where('type', 'yes')->get();
        $count = $count + count($data);

        foreach ($data as $value) {
            if ($value->type == "yes") {
                $downline_user[$index]['user_id']             = $value->id;
                static::$downline_users[$index]['user_id']    = $value->user_id;
                static::$downline_users[$index]['join_month'] = date("m", strtotime($value->created_at));
                $index++;
                self::getDownlines($index, false, $value->id, $downline_user, $level + 1, $count);

            }
        }

    }
    public static function getDownlineCount($user_id, $index = 0, $downline_users = array())
    {
        $users = self::where('placement_id', $user_id)->where('type', 'yes')->get();
        for ($i = 0; $i < count($users); $i++) {
            $index                  = $index + ($i + 1);
            $downline_users[$index] = $users[$i]->user_id;
            self::getDownlineCount($users[$i]->user_id, $index, $downline_users);
        }

    }
    public static function getDown()
    {
        $count_users = count(static::$downline_users);
        $month_count;
        for ($k = 1; $k < 13; $k++) {$month_count[$k] = 0;}
        for ($j = 1; $j <= $count_users; $j++) {
            if (!empty(static::$downline_users)) {
                if (static::$downline_users[$j]['join_month'] == 1) {$month_count[1] += 1;} else if (static::$downline_users[$j]['join_month'] == 2) {$month_count[2] += 1;} else if (static::$downline_users[$j]['join_month'] == 3) {$month_count[3] += 1;} else if (static::$downline_users[$j]['join_month'] == 4) {$month_count[4] += 1;} else if (static::$downline_users[$j]['join_month'] == 5) {$month_count[5] += 1;} else if (static::$downline_users[$j]['join_month'] == 6) {$month_count[6] += 1;} else if (static::$downline_users[$j]['join_month'] == 7) {$month_count[7] += 1;} else if (static::$downline_users[$j]['join_month'] == 8) {$month_count[8] += 1;} else if (static::$downline_users[$j]['join_month'] == 9) {$month_count[9] += 1;} else if (static::$downline_users[$j]['join_month'] == 10) {$month_count[10] += 1;} else if (static::$downline_users[$j]['join_month'] == 11) {$month_count[11] += 1;} else { $month_count[$j] += 1;}
            }
        }
        $month = $month_count[1] . "," . $month_count[2] . "," . $month_count[3] . "," . $month_count[4] . "," . $month_count[5] . "," . $month_count[6]
            . "," . $month_count[7] . "," . $month_count[8] . "," . $month_count[9] . "," . $month_count[10] . "," . $month_count[11] . "," . $month_count[12];
        // print_r($month);
    }

    public static function getAllUpline($user_id)
    {

        $result = SELF::join('profile_infos', 'profile_infos.user_id', '=', 'tree_table.placement_id')
            ->where('tree_table.user_id', $user_id)
            ->select('tree_table.leg', 'tree_table.placement_id', 'tree_table.type', 'profile_infos.package')
            ->get();       

        foreach ($result as $key => $value) {
            if ($value->type != 'vaccant' && $value->placement_id > 1) {
                SELF::$upline_users[]   = ['user_id' => $value->placement_id, 'leg' => $value->leg, 'package' => $value->package];
                SELF::$upline_id_list[] = $value->placement_id;
            }

            if ($value->placement_id > 1) {
                SELF::getAllUpline($value->placement_id);
            }
        }

        return true;

    }

    public static function getUserLeg($user_id)
    {

        return self::where('user_id', $user_id)->value('leg');
    }

    public static function getFatherID($user_id)
    {

        return self::where('user_id', $user_id)->value('placement_id');
    }

    //RELATIONSHIPS - Added By Aslam
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
