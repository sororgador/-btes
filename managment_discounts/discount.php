<?php
include_once("connection.php");
session_start();
class Discount {
    // الخصائص
    private $discountId;
    private $discountValue;
    private $discountType;
    private $eventId;

    // Constructor
    public function __construct($discountId, $discountValue, $discountType, $eventId)
	{
        $this->discountId = $discountId;
        $this->discountValue = $discountValue;
        $this->discountType = $discountType;
        $this->eventId = $eventId;
    }
 
public function display_form($conn)
{?>
   <form method ="post" action="#">
              <h2>insert into discount:  </h2>
	 discount id:<input type ="text" name = "discountId"><br><br>
     discount type:<input type="text" name="type"/><br><br>
	 discount value:<input type="text" name="value"/><br><br>
	 event id: <input type="text" name="event"/><br><br>  
	 <br><input name="ok" type="submit" value="insert"/> 
    </form>
<?php 
} //end function 
///////////////////////////////////////////////////////////////
//----------------- function insert------------------------------------------
public function addDiscount($conn)
{
	
$discountId=$_POST['discountId'];
$discountType=$_POST['type'];
$discountValue=$_POST['value'];
$eventId=$_POST['event'];
try{	
	
	$sql="INSERT INTO discount(discount_id,type,event_id,discount_value)
	values( '$discountId' ,'$discountType', '$eventId' , '$discountValue')";
	$conn->exec($sql);
    echo "Record insert successfully "; 
 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
}
////////////////////////////////////////////////////
//-------------------delete-------------------
public function displayformsearch($conn)
{?>
  <form method="post" action="#">
     <select name="discount">
         <option></option>
           <?php $this->fillIn($conn);?>
     </select>
    <input type="submit" name="select" value="select discount">
  </form>
	<?php
}
//////////////////////////////////////////////////////
public function fillIn($conn)
{
	  try
      {
        $sql="SELECT * FROM discount";
        $rows=$conn->query($sql);
      if($rows->rowCount() != 0)
	  {	 
        while($row=$rows->fetch(PDO::FETCH_OBJ))
         {   
             ?> <option value="<?php echo $row->discount_id;?>"/>
			 <?php echo $row->type , "      "  ,$row->discount_value;?></option><?php 
         }
       }
	   else
	   {  
         echo "NO RECORDS";
	   }
	  }
     catch(PDOException $e)
     {
     echo $sql . "<br>" . $e->getMessage();
     }
  

}
//////////////////////////////////////////////
public function deleteDiscount($conn)
{
   $id=$_POST['discount'];
   try{
  $sql="SELECT * FROM discount where discount_id='$id' ";
  $row=$conn->query($sql);
  $row1=$row->fetch(PDO::FETCH_OBJ);
  $n=$row->rowCount();
   }
     catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
  if($n != 0)
  {	  
   try{
     $sql="delete from discount where discount_id='$id' ";
     $conn->exec($sql);
	 echo "delete is done";
      }
   catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
  }
  else{
	  echo "NOT FOUND";
  }

}

//////////////////////////////////////////////
public function deleteDiscount($conn)
{
   $id=$_POST['id'];
   try{
  $sql="SELECT * FROM discount where discount_id='$id' ";
  $row=$conn->query($sql);
  $row1=$row->fetch(PDO::FETCH_OBJ);
  $n=$row->rowCount();
   }
     catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
  if($n != 0)
  {	  
   try{
     $sql="delete from discount where discount_id='$id' ";
     $conn->exec($sql);
	 echo "delete is done";
      }
   catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
  }
  else{
	  echo "NOT FOUND";
  }

}
//////////////////////////////////////////////
 public function formupdate($connn,$discountValue,$discountType,$event_id)
{?>
   <form method ="post" action="#">
              <h2>update discount:  </h2>
     discount type:<input type="text" name="type" value=<?php echo $discountType ?>/><br><br>
	 discount value:<input type="text" name="value" value=<?php echo $discountValue ?>/><br><br>
	 event id: <input type="text" name="event" value=<?php echo $event_id ?>/><br><br>  
	 <br><input name="ok" type="submit" value="insert"/> 
    </form>
  <?php
}
/////////////////////////////////////////////////
public function dataEdit($conn)
{
	$discountId=$_SESSION['id']; 
	   try
    {
       $sql="SELECT * FROM discount where discount_id='$discountId'";
	   $rows=$conn->query($sql);
	   if($rows->rowCount() != 0)
	   {
       $row=$rows->fetch(PDO::FETCH_OBJ);
                   $discountValue=$row->discount_value;
                   $discountType=$row->type;
                   $eventId=$row->event_id;
	                        
			$this->formupdate($conn,$discountValue,$discountType,$eventId);
	   }
       else 
	   {
		   echo "not found";
	   }		   
    }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }  
	
}
/////////////////////////////////////////////////////////////
public function update($conn)
{
$discountId=$_SESSION['id'];
$type=$_POST['type'];
$event=$_POST['event'];
$value=$_POST['value'];
 
 
   try{
  $query="UPDATE discount SET  type='$type',
  discount_value='$value' , event_id='$event' where discount_id=$discountId";
  $conn->exec($query);
    echo "Record update successfully";
    
      }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }
  
  }
 
///////////////////////////////////////////
function get_discount($number,$price)
{
	$total=$price*$number;
	echo $total;
}+
 
}//end class
 
// إنشاء كائن من Discount
$discount = new Discount(1, 20.5, "نسبة مئوية", 101);


?>

 
