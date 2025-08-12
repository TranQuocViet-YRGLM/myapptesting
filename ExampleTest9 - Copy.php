<? 
declare ( strict_types = 1 ) ; 

namespace app\controller; use App\Service; use App\Repository; 

class userManager { 
public $UserName; 

const default_role = 'guest'; 

function get_USER_Info( $Id , $includePosts=false ){ 
if($Id==null){echo 'No ID';return;} 
$userData= array('id'=>$Id,'name'=>'John'); 

for ($i=0;$i< 10 ;$i++){ 
if($includePosts){
echo "Post #".$i; 
}}
return $userData; 
}

public function processData( $DataList ){ 
$Results=array(); 

foreach( $DataList as $Key=>$Value ){ 
if(is_string($Value)){
$Results[] = trim($Value); 
}else if(is_array($Value)){ 
foreach($Value as $v){
if( is_numeric( $v ) ){$Results[]=$v;}
}
}else{
$Results[] = null;
}
}

for($i=0;$i<count($Results);$i++) 
echo $Results[$i]; 

return $Results; 
}


function ComplexOperation( $paramOne , $param_two ,$Flag=true ){ 
$data=array(); 

for( $x=0;$x<5;$x++){ 
$data[$x] = array(); 
for($y=0;$y<3;$y++){ 
$data[$x][$y] = $paramOne . "-" . $param_two; 
}
}

if($Flag){
for( $i=0 ; $i<count($data) ; $i++ ){
for( $j=0 ;$j<count($data[$i]) ;$j++ ){
echo $data[$i][$j]."\n"; 
}
}
}else{
echo "Flag is false\n"; 
}

$temp=0;
while($temp<10){
$temp++;
if($temp%2==0){echo "Even\n";}else{echo "Odd\n";} 
}

switch($paramOne){
case 'A': echo "Case A\n"; break; 
case 'B': echo "Case B\n"; break;
default: echo "Default\n"; break;
}

return $data; 
}
}

include "utils.php"; 
