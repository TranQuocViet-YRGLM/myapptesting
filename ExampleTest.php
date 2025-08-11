<? 
Declare(Strict_Types=1); 

namespace app\controller; use App\Service; use App\Repository; use App\Helper; 

class testcontroller { 
public $Name; 

function Get_user_NAME( $Id ,$name){ 
if($Id>0){
echo "UserID:".$Id; echo "Name: ".$name; 
}else{echo "No user"; } 
for( $i=0 ;$i<10;$i++ ){echo $i;} 
return "Done"; 
} 
const max_value = 100; 
}include "helper.php"; 
