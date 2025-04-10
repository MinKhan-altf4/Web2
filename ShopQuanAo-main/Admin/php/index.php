<?php
//1.In ra màn hình
/* có thể chèn html như thế này */
/* */
echo "Hello <br> <h1> Zawe </h1>";

/*2.Biến:bắt buộc là chữ đầu tiên */
$tenbien = "zawe yêu ngokngok";
$namsinh = 2005;

echo $tenbien . "<br>". $namsinh;

/*3.Hằng:tên,giá trị,true:không phân biệt in hoa hay thường */

define('hang','10',true);
echo hang;

/*4."" và '':khi dùng "" thì vẫn nhận là biến nhưng '' sẽ làm biến thành String */

/*5.Print: nếu giá trị trong echo là đếm từng chữ còn print thì luôn là 1  */



/*6.If else */

$ten = "ngoc";
if($ten == "zawe"){
    echo "Sinh nam 2005 <br>";
}
elseif($ten == "ngoc"){
 echo "Sinh nam 2009 <br>";
}
else{
    echo "Khong hop le <br>";
}

/*7.Chuỗi: strlen là lấy độ dài của chuỗi
           str_word_count dem theo cum tu
           strrev là dảo ngược chuỗi
           strpos kiếm vị trí của từ
           str_replace thay thế từ */
echo strlen("Zawe");



/*8.Kiểm tra dữ liệu:vardum */

/*9.switch 

switch(biến){
    case điều kiện ;
    thực hiện code
    break;
}
*/
/*10.foreach: vòng lặp in giá trị của mảng
for với mảng thì dùng count(biến)
                      biến[vi tri bien]*/


/*11.isset():kiểm tra biến có tồn tại hay không */  

/*12.declare:yêu cầu nghiêm ngặt */

/*13.Array: 
thêm: $bien[] = "giá trị muốn thêm sửa"
sửa : $bien[vị trí muốn sửa] = "giá trị muốn sửa"
xóa: unset($bien[vị trí muốn xóa])
sort():sắp xếp array theo thứ tự tăng dần
rort():sắp xếp array theo thứ tự giảm dần
explode(): chuyển string sang array
implode(): chuyển array sang string */

/*14.readfile():đọc file
     fopen("ten file","r:quyền đọc file")or die ("lỗi"): mở file
     fread(bien,filesize("file.txt")): đọc file
     fclose(biến):đóng file
      fopen("ten file","w:quyền tạo file mới")  
      fopen("ten file","a:quyền viết vào file mới")
      fwrite(tên biến,"nội dung file"):ghi vào file*/



/*15.Lọc số nguyên: if(!filter_var(bien , FILTER_VALIDATE_INT) == false)
     Lọc chuỗi : filter_var(bien,FILTER_SANITIZE_STRING) 
     Lọc IP: if(!filter_var(bien , FILTER_VARIDATE_IP) == false)
     Lọc Email: biến = filter_var(biến email,FILTER_SANITIZE_STRING)
                if(!filter_var(filter_var , FILTER_VALIDATE_EMAIL) == false)
     Lọc URL:giống lọc Email nhưng thay chỗ email thành URL */



/*16.Chuyển trang : header('location:file') */

?>