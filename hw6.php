<html>
<head>
    <script type="text/javascript">
        function validateForm(form) {
            document.getElementById('search_results').innerHTML = '';
            msg = '';
            errorFound = false;
            if(form.street.value.trim().length == 0){
                msg ='Please enter the value for Street';
                errorFound = true;
            }
            if(form.city.value.trim().length == 0){
                msg+= (msg!='') ?  ', City' : 'Please enter the value for City';
                errorFound = true;
            }
            if(form.state.options[form.state.selectedIndex].value.trim().length == 0){
                msg+= (msg!='') ?  ' and State' : 'Please enter the value for State';
                errorFound = true;
            }
            if(msg.indexOf('State') == -1){
                msg = msg.replace(',',' and');
            }
            if(errorFound) {
                alert(msg);
                return false;
            }
            return true;
        }        
    </script>
</head>
<body>
<form method='POST' onsubmit ="return validateForm(this)">
<?php include 'sss.php' ?>
    <center>
        <h2>Real Estate Search</h2>
        <fieldset style='width:50%;background-color:#4A66A0'>
            <table style = 'color:white'>
                <tr>
                    <td>Street Address <font color = 'red'>*</font>:</td>
                    <td><input type= 'text' name = 'street' placeholder = 'Enter Street' style = 'width:250px' value = "<?php echo $street; ?>"></td>
                </tr>
                <tr>
                    <td>City <font color = 'red'>*</font>:</td>
                    <td><input type= 'text' name = 'city' placeholder = 'Enter City' style = 'width:250px' value = "<?php echo $city; ?>" ></td>                            
                </tr>  
                <tr>
                    <td>State <font color = 'red'>*</font>:</td>
                    <td>                       
                        <select name = 'state' id= 'name' style='width:250px'>
                        <?php 
                            $state_list = array('','AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','HI','IA','ID','IL','IN','KS','KY','LA','MA','MD','ME','MI','MN','MO','MS','MT','NC','ND','NE','NH','NJ','NM','NV','NY','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VA','VT','WA','WI','WV','WY');
                            $state_text = '';
                            foreach($state_list as $st){
                                if($state == $st){
                                    $state_text.= "<option value='".$st."' selected>".$st."</option>";
                                }else{
                                    $state_text.= "<option value='".$st."'>".$st."</option>";
                                }
                            }
                            echo $state_text;
                        ?>                             
                        </select></td>                            
                </tr>                
                <tr>
                    <td colspan = '2' style = 'text-align:center'><br><input type = 'submit' name = 'submit_query' value = 'Search' style= 'width:150px'></td>
                </tr>
                <tr>
                    <td colspan = '2' style = 'font-style:italic'><br><font color = 'red'>*</font> - Mandatory Fields</td>
                </tr>
            </table>
            <p align = 'right'><img src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /></p>          
        </fieldset>
    </center>
</form>
<div id = 'search_results' align='center'><?php echo $html_text; ?></div>
</body>
</html>