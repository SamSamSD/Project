<!DOCTYPE html>
<html>

    <?php include("head.php"); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
  <body class="skin-blue">
    <?php include("header.php"); ?>
      
    <?php include("slide_menu.php"); ?>

      <!-- Right side column. Contains the navbar and content of the page -->
      <div class="content-wrapper" >
        <!-- Content Header (Page header) -->
        <div class="body">
         <!-- Main content -->  
         <div class="box-header">
            <a class="path" href="edit_check-service.php">/ กำหนดการออกตรวจ</a><a class="path" href="check-service_list.php?cs_no=<?php echo $_GET['cs_no']; ?>">/ ข้อมูลการออกตรวจ</a><a style="color: black;text-decoration-line: none;" href="">/ สถานะการตรวจสุขภาพ</a>
            </div>
         <div >
         
          <form method='POST' action="" >
            <label >เลือก กำหนดาการออกตรวจสุขภาพ</label>
           <select name='check_service' required>
                <option value="">--- เลือก ---</option>
                <?php
                    $sqlCommand = "SELECT cs.cs_no,cs.cs_date,c.comp_name
                    FROM check_service as cs  LEFT JOIN company_address as ca ON cs.ca_id = ca.ca_id
                                              LEFT JOIN company as c ON ca.comp_id = c.comp_id";
                    $result=mysqli_query($con,$sqlCommand);
                    while ($row3=mysqli_fetch_array($result)) {
                        echo "<option value='".$row3[0]."'>".$row3[1]." ".$row3[2]."</option>";
                    }
                    
                ?>
            </select>
            <input style="height: 30px; width: 50px;padding: 3px;" class="btn btn-primary" type="submit" name="submit_emp_list" value="submit">
          </form>
          </div>

          <?php if (isset($_POST['submit_emp_list'])||isset($_GET['cs_no'])) { 
             if(isset($_POST['submit_emp_list']))
             {$sqlcp = "SELECT cp.comp_name,cs.cs_date
                                         FROM company as cp  LEFT JOIN company_address as ca ON ca.comp_id=cp.comp_id
                                                             LEFT JOIN check_service as cs ON cs.ca_id=ca.ca_id 
                                         WHERE cs.cs_no = $_POST[check_service]";
                         $querycp=mysqli_query($con,$sqlcp);
                         $cp=mysqli_fetch_array($querycp);
              }
              else
              {$sqlcp = "SELECT cp.comp_name,cs.cs_date
                                         FROM company as cp  LEFT JOIN company_address as ca ON ca.comp_id=cp.comp_id
                                                             LEFT JOIN check_service as cs ON cs.ca_id=ca.ca_id 
                                         WHERE cs.cs_no = $_GET[cs_no]";
                         $querycp=mysqli_query($con,$sqlcp);
                         $cp=mysqli_fetch_array($querycp);
              }
              
            //$date = strtotime($cp['cs_date']);
            //echo date("d.m.Y ", strtotime($cp["cs_date"]));
            ?>
         <div class="page-break" " >
          <div id="printarea">
          <?php include('function.php'); ?>
          <center><h2>สถานะการตรวจสุขภาพพนักงานบริษัท <?php echo $cp["comp_name"]; ?> วันที่ <?php echo DateThaiShow($cp["cs_date"]); ?></h2></center>
                  
        <?php
            include('connection.php');
            if(isset($_POST['submit_emp_list']))
            {$sqllist = "SELECT DISTINCT `checklist_name_tag` 
                          FROM `program_check_detail`as pcd JOIN program_check_u as pcu ON pcd.checklist_id = pcu.checklist_id
                                                            JOIN check_service_detail as csd ON csd.pro_id = pcu.pro_id
                          WHERE csd.cs_no = '$_POST[check_service]'";
                        $querylist=mysqli_query($con,$sqllist);
              $sqlemp ="SELECT emp.*,pc.pro_id,csd.cs_no 
                        FROM employee as emp JOIN check_list as cl ON emp.emp_id = cl.emp_id
                                              JOIN check_service_detail as csd ON cl.csd_no = csd.csd_no
                                              JOIN program_check as pc ON csd.pro_id = pc.pro_id
                        WHERE csd.cs_no = '$_POST[check_service]'";
                        $queryemp=mysqli_query($con,$sqlemp);

            }
            else
            {$sqllist = "SELECT DISTINCT `checklist_name_tag` 
                          FROM `program_check_detail`as pcd JOIN program_check_u as pcu ON pcd.checklist_id = pcu.checklist_id
                                                            JOIN check_service_detail as csd ON csd.pro_id = pcu.pro_id
                          WHERE csd.cs_no = '$_GET[cs_no]'";
                        $querylist=mysqli_query($con,$sqllist);
              $sqlemp ="SELECT emp.*,pc.pro_id ,csd.cs_no
                        FROM employee as emp JOIN check_list as cl ON emp.emp_id = cl.emp_id
                                              JOIN check_service_detail as csd ON cl.csd_no = csd.csd_no
                                              JOIN program_check as pc ON csd.pro_id = pc.pro_id
                        WHERE csd.cs_no = '$_GET[cs_no]'";
                        $queryemp=mysqli_query($con,$sqlemp);
            }?>
            <div style="overflow-x:auto;" >
            <table id="tablepage-doctor" class="display" width="100%" >
                 <thead >
                  <tr align="center">
                      <th align="center">ลำดับ</th>
                      <th align="center"> H.N</th>
                      <th align="center">V.N</th>
                      <th align="center">ชื่อ-สกุล</th>
                      <th align="center">โปรแกรม</th>
                      <?php 
                        $tag = array();
                        $nub=0;
                        while ($row=mysqli_fetch_array($querylist)) {
                            array_push($tag,$row['checklist_name_tag']);
                            $nub++;
                          ?>
                          <th align="center"><?php echo $row["checklist_name_tag"]; ?></th>
                      <?php    
                        }
                      ?>
                      <th align="center">สถานะ</th>
                      

                  </tr>
                </thead>
                <tbody>
            <?php
            while($row=mysqli_fetch_array($queryemp))
                { ?>
              <tr>
                  <td align="center"><?php echo $row["emp_no"]; ?></td>
                  <td align="center"><?php echo $row["emp_id"]; ?></td>
                  <td align="center"><?php echo $row["VN"]; ?></td>
                  <td><?php echo $row["emp_title"]; ?>&nbsp;<?php echo $row["emp_name"]; ?>&nbsp;&nbsp;<?php echo $row["emp_surname"]; ?></td>
                  <td align="center"><?php echo $row["pro_id"]; ?></td>

                  <?php 
                  $nub2=0;
                  $tagemp = array();
                  $sqlstatus = "SELECT ct.tag,ct.tag_status
                                FROM employee as emp JOIN check_list as cl ON emp.emp_id = cl.emp_id
                                                      JOIN check_service_detail as csd ON cl.csd_no = csd.csd_no
                                                      JOIN program_check as pc ON csd.pro_id = pc.pro_id
                                                      JOIN check_tags as ct ON ct.check_id = cl.check_id
                                WHERE csd.cs_no = '$row[cs_no]' AND emp.emp_id ='$row[emp_id]'";
                      $querystatus=mysqli_query($con,$sqlstatus);
                     while ($row2=mysqli_fetch_array($querystatus,MYSQLI_ASSOC)) {
                            $tagemp[] = array(
                                  'tag'=> $row2['tag'],
                                  'status'=> $row2['tag_status']
                              );
                            $nub2++;
                        }

            


                      $status=0;
                     for($j=0;$j<$nub2;$j++) {
                            for ($i=0;$i<$nub;$i++) 
                            { 
                              if($tag[$i]==$tagemp[$j]['tag']){

                                if($tagemp[$j]['status']==1){
                                  echo "<td align='center'>&#10004</td>";
                                  $status++;
                                }else{
                                  echo "<td align='center'></td> ";
                                }
                                //echo "<td align='center'> ".$tagemp[$j]['status']." </td>";
                                if($j<$nub2){
                                  $j++;
                                }
                                if($j>=$nub2){
                                  $j--;
                                }

                              }
                              else{
                                echo "<td align='center'>&#10008</i></td>";
                              }
                            }

                      }
                      if($status==$nub2){
                        echo "<td align='center' bgcolor='#33ff77'>ตรวจครบ</td>";
                      }else{
                        echo "<td align='center' bgcolor='#ff3333' >ไม่ครบ</td> ";
                      }

                  ?>
                  

              </tr>

                        

                  
               <?php }

                echo "</tbody>"; 
             echo "</table>";
             echo "</div>";       
        }
        if (isset($_POST["clear"])) {
        system('clear');
        }
    ?>            

        </div>
     </div>
    </div>

    <script type="text/javascript">
        
        function PrintDoc() {

            var toPrint = document.getElementById('printarea');

            var popupWin = window.open('', '_blank', 'width=350,height=150,location=no,left=200px');

            popupWin.document.open();

            popupWin.document.write('<html><head><title>::Preview::</title><link rel="stylesheet" type="text/css"  /><style type="text/css" media="print">@page { size: landscape; } table{border-collapse: collapse;border: 1px solid black;}</style></head><body onload="window.print()">')

            popupWin.document.write(toPrint.innerHTML);

            popupWin.document.write('</html>');

            popupWin.document.close();

        }

    </script>
    <?php 
        include("footer.php");
        include("js/DataTable.js");
    ?>
  </body>
</html>