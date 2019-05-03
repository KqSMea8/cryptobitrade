<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class PointTable extends Model
{


    // use softDeletes; 
    protected $table = 'point_table';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','pv','redeem_pv','right_carry','left_carry','total_left','total_right'];


    public function userR()
    {
        return $this->hasOne('App\User','id','user_id');
    }



    public static function addPointTable($user_id){

    	self::create([
    		'user_id'=>$user_id,
    		'redeem_pv'=>0,
    		'pv'=>0,
    		]);
    } 

   

    public static function getUserPoint($user_id){

        return self::where('user_id',$user_id)->get();
    }


     public static function updatePoint($bv_update,$from_id){

        $variable = Tree_Table::$upline_users;

        // $daily_limit = Packages::pluck('daily_limit','id');

        foreach ($variable as $key => $value) {
            if($value['leg'] == 'L'){
                self::where('user_id',$value['user_id'])->increment('left_carry',$bv_update);
                self::where('user_id',$value['user_id'])->increment('total_left',$bv_update);
            }else{
                self::where('user_id',$value['user_id'])->increment('right_carry',$bv_update);
                self::where('user_id',$value['user_id'])->increment('total_right',$bv_update);
            }  
                self::where('user_id',$value['user_id'])->increment('pv',$bv_update);
                PointHistory::addPointHistoryTable($value['user_id'],$from_id,$bv_update,$value['leg'],'binary');
        }
    }
    public static function pairing(){
      
        $users_list = self::where('point_table.left_carry' ,'>',0)
                     ->where('point_table.right_carry','>',0)                         
                     ->select('point_table.*')
                     ->get();

                     foreach ($users_list as $key => $value) {
                        echo " ------------ " ;
                        $pv_pair = min($value->left_carry,$value->right_carry);
                        $total_amount =   $pv_pair * 10 /100 ;
                        Commission::create([
                            'user_id'=>$value->user_id,
                            'from_id'=>$value->user_id,
                            'total_amount'=>$total_amount,
                            'tds'=>0,
                            'service_charge'=>0,
                            'payable_amount'=>$total_amount,
                            'payment_type'=>'binary_bonus',
                            ]);
                        Balance::where('user_id',$value->user_id)->increment('balance',$total_amount);
                   
                        SELF::where('user_id','=',$value->user_id)->decrement('left_carry',$pv_pair);
                        SELF::where('user_id','=',$value->user_id)->decrement('right_carry',$pv_pair);

                        SELF::matchingbonus($value->user_id, $value->user_id,$total_amount);
                    }
                }


        public  static function matchingbonus($from_id ,$user_id , $amount, $level =0 ){

            $level_bonus = [5,3,2] ;
            $bonus_percent = $level_bonus[$level] ;//self::where('package_id',$package_id)->value("$column");
             $sponsor_id = Sponsortree::where('user_id',$user_id)->value('sponsor');
             if($bonus_percent){
                $total_amount = $amount * $bonus_percent / 100 ;

                 $settings = Settings::getSettings();  
                 // $sponsor_commisions =7;// $settings[0]->sponsor_Commisions;
                 $tds = $total_amount * $settings[0]->tds / 100;
                 $service_charge = $total_amount * $settings[0]->service_charge / 100;
                 $payable_amount = $total_amount - $tds - $service_charge;

                Commission::create([
                        'user_id'=>$sponsor_id,
                        'from_id'=>$from_id,
                        'total_amount'=>$total_amount ,
                        'tds'            => $tds,
                        'service_charge' => $service_charge,
                        'payable_amount'=>$payable_amount ,
                        'payment_type'=>'matching_bonus',
                        ]);
                Balance::where('user_id',$user_id)->increment('balance',$payable_amount);
               

                if($level <= 1 && $sponsor_id > 0){
                    return self::matchingbonus($from_id,$sponsor_id,$amount,++$level);
                }
            }
            return ;





        }

    }
