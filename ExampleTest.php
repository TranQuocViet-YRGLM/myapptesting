<? // (1) Sai: short tag, không dùng <?php
Declare(Strict_Types=1); // (2) Sai: không viết thường, (3) Sai: có space thừa

namespace app\controller; use App\Service; use App\Repository; use App\Helper; // (4) Sai: namespace lowercase, (5) Sai: use trên cùng 1 dòng

class testcontroller { // (6) Sai: class tên không PascalCase
public $Name; // (7) Sai: property viết PascalCase, (8) Sai: thiếu type hint, (9) Sai: không private/protected/public hợp lý theo context

function Get_user_NAME( $Id ,$name){ // (10) Sai: tên method không camelCase, (11) Sai: space sai vị trí, (12) Sai: biến không camelCase
if($Id>0){
echo "UserID:".$Id; echo "Name: ".$name; // (13) Sai: không xuống dòng hợp lý
}else{echo "No user"; } // (14) Sai: else không xuống dòng chuẩn
for( $i=0 ;$i<10;$i++ ){echo $i;} // (15) Sai: khoảng trắng không đúng chuẩn
return "Done"; // (16) Sai: return kiểu string nhưng không khai báo type
} // (17) Sai: đóng } không thụt đầu dòng đúng
const max_value = 100; // (18) Sai: hằng số không viết IN_CAPS
}include "helper.php"; // (19) Sai: trộn code thực thi với khai báo class
