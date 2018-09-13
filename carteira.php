<!DOCTYPE html>
<?php
   include('session.php');
?>
<!-- AJAX -->
<script language = "javascript" type = "text/javascript">
   <!--
      //Browser Support Code
      function ajaxFunction(){
         var ajaxRequest;  // The variable that makes Ajax possible!
         
         try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
         }catch (e) {
            // Internet Explorer Browsers
            try {
               ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            }catch (e) {
               try{
                  ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
               }catch (e){
                  // Something went wrong
                  alert("Your browser broke!");
                  return false;
               }
            }
         }
         
         // Create a function that will receive data 
         // sent from the server and will update
         // div section in the same page.
    
         ajaxRequest.onreadystatechange = function(){
            if(ajaxRequest.readyState == 4){
               var ajaxDisplay = document.getElementById('ajaxDiv');
               ajaxDisplay.innerHTML = ajaxRequest.responseText;
            }
         }
         
         // Now get the value from user and pass it to
         // server script.
    
         var age = document.getElementById('age').value;
         var wpm = document.getElementById('wpm').value;
         var sex = document.getElementById('sex').value;
         var queryString = "?age=" + age ;
      
         queryString +=  "&wpm=" + wpm + "&sex=" + sex;
         ajaxRequest.open("GET", "get-carteira.php" + queryString, true);
         ajaxRequest.send(null); 
      }
   //-->
</script>

<html lang="en">
<head>
  <title>Controle de investimentos</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!--<script language="javascript"> window.document.onload = ajaxFunction </script>
  <script type="text/javascript"> window.onload = ajaxFunction() </script> -->
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
  </style>
</head>
<body>


   <!-- Barra de navegação -->
   <nav class="navbar navbar-inverse">
     <div class="container-fluid">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>
           <span class="icon-bar"></span>                        
         </button>
         <a class="navbar-brand" href="#">Logo</a>
       </div>
       <div class="collapse navbar-collapse" id="myNavbar">
         <ul class="nav navbar-nav">
           <li class="active"><a href="./index.php">Início</a></li>
           <li><a href="./sobre.php">Sobre</a></li>
           <li><a href="./contato.php">Contato</a></li>
         </ul>
         <ul class="nav navbar-nav navbar-right">
           <li><a href="./login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
         </ul>
       </div>
     </div>
   </nav>
      
      

		
      <form name = 'myForm'>
         Max Age: <input type = 'text' id = 'age' /> <br />
         Max WPM: <input type = 'text' id = 'wpm' />
         <br />
         
         Sex: <select id = 'sex'>
            <option value = "m">m</option>
            <option value = "f">f</option>
         </select>
			
         <input type = 'button' onclick = 'ajaxFunction()' value = 'Query MySQL'/>
			
      </form>
      
      <div id = 'ajaxDiv'>Your result will display here</div>
   </body>
</html>