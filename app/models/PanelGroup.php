<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\models\Stage;
use DB;

class PanelGroup extends Model
{
    protected $table = "panel_group";
    public $primaryKey = "panelGroupID";
    public $timestamps = false;
    public $incrementing = false;

    public function current($groupID) {
        $stage = new Stage;
        return $x = DB::table('panel_group')
        ->join('group','group.groupID','=','panel_group.panelCGroupID')
        ->where('panel_group.panelCGroupID','=',$groupID)
        ->where('panel_group.panelGroupType','=',$stage->current($groupID))
        ->get(); 
    }
}
