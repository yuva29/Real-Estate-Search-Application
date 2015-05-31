<?php

$street = isset($_POST['street']) ? $_POST['street'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';   
$state = isset($_POST['state']) ? $_POST['state'] : ''; 
$zws_id = 'X1-ZWz1b2yek9wjrf_7aoby';

//Replace spaces by '+'
$street = preg_replace("/[\s]+/", "+", $street);
$city = preg_replace("/[\s]+/", "+", $city);
$html_text = '';
date_default_timezone_set('America/Los_Angeles');
    
if(isset($_POST['submit_query'])) { // Submit action
    $url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm?';
    $request = $url."zws-id=".$zws_id."&address=".$street."&citystatezip=".$city.','.$state."&rentzestimate=true";
    $url = urlencode($url);
    $response = array();
    $response = simplexml_load_file($request);
    //$html_text.=$request;
    //print_r($request);
    
    if(!empty($response) && $response->message->code == 0) {
        if($response->response && $response->response->results && $response->response->results->result) {
            $responseResult = $response->response->results->result;
            $result_table = array();
            $result_table['result_header'] = ($responseResult->links && $responseResult->links->homedetails && strlen(trim($responseResult->links->homedetails))>0) ? $responseResult->links->homedetails : 'NA';
            $result_table['property_type'] = ($responseResult->useCode && strlen(trim($responseResult ->useCode))>0) ? $responseResult->useCode : 'NA';
            $result_table['year_built'] = ($responseResult->yearBuilt && strlen(trim($responseResult->yearBuilt))>0) ? $responseResult->yearBuilt : 'NA';
            $result_table['lot_size'] = ($responseResult->lotSizeSqFt && strlen(trim($responseResult->lotSizeSqFt))>0) ? $responseResult->lotSizeSqFt : 'NA';
            $result_table['sq_ft'] = ($responseResult->finishedSqFt && strlen(trim($responseResult->finishedSqFt))>0) ? $responseResult->finishedSqFt : 'NA';
            $result_table['bathrooms'] = ($responseResult->bathrooms && strlen(trim($responseResult->bathrooms))>0) ? $responseResult->bathrooms : 'NA';
            $result_table['bedrooms'] = ($responseResult->bedrooms && strlen(trim($responseResult->bedrooms))>0) ? $responseResult->bedrooms : 'NA';
            $result_table['tax_assessment_yr'] = ($responseResult->taxAssessmentYear && strlen(trim($responseResult->taxAssessmentYear))>0) ? $responseResult->taxAssessmentYear : 'NA';
            $result_table['tax_assessment'] = ($responseResult->taxAssessment && strlen(trim($responseResult->taxAssessment))>0) ? $responseResult->taxAssessment : 'NA';
            $result_table['last_sold_price'] = ($responseResult->lastSoldPrice && strlen(trim($responseResult->lastSoldPrice))>0) ? $responseResult->lastSoldPrice : 'NA';
            $result_table['last_sold_date'] = ($responseResult->lastSoldDate && strlen(trim($responseResult->lastSoldDate))>0) ? $responseResult->lastSoldDate : 'NA';
            $result_table['overall_change'] = ($responseResult->zestimate && $responseResult->zestimate->valueChange && strlen(trim($responseResult->zestimate->valueChange))>0) ? $responseResult->zestimate->valueChange : 'NA';
            $result_table['zestimate_valuation_low'] = ($responseResult->zestimate && $responseResult->zestimate->valuationRange && $responseResult->zestimate->valuationRange->low && strlen(trim($responseResult->zestimate->valuationRange->low))>0) ?  $responseResult->zestimate->valuationRange->low : 'NA';
            $result_table['zestimate_valuation_high'] = ($responseResult->zestimate && $responseResult->zestimate->valuationRange && $responseResult->zestimate->valuationRange->high && strlen(trim($responseResult->zestimate->valuationRange->high))>0) ? $responseResult->zestimate->valuationRange->high : 'NA';
            $result_table['rent_change'] = ($responseResult->rentzestimate && $responseResult->rentzestimate->valueChange && strlen(trim($responseResult->rentzestimate->valueChange))>0) ? $responseResult->rentzestimate->valueChange : 'NA';
            $result_table['rentzestimate_valuation_low'] = ($responseResult->rentzestimate && $responseResult->rentzestimate->valuationRange && $responseResult->rentzestimate->valuationRange->low && strlen(trim($responseResult->rentzestimate->valuationRange->low))>0) ? $responseResult->rentzestimate->valuationRange->low : 'NA';
            $result_table['rentzestimate_valuation_high'] =($responseResult->rentzestimate && $responseResult->rentzestimate->valuationRange && $responseResult->rentzestimate->valuationRange->high && strlen(trim($responseResult->rentzestimate->valuationRange->high))>0) ? $responseResult->rentzestimate->valuationRange->high : 'NA';
            $result_table['zestimate_last_updated'] = $responseResult->zestimate->{'last-updated'};
            $result_table['rentzestimate_last_updated'] = $responseResult->rentzestimate->{'last-updated'};    
            $result_table['zestimate_amount'] = ($responseResult->zestimate && $responseResult->zestimate->amount && strlen(trim($responseResult->zestimate->amount))>0) ? $responseResult->zestimate->amount : 'NA';
            $result_table['rentzestimate_amount'] = ($responseResult->rentzestimate && $responseResult->rentzestimate->amount && strlen(trim($responseResult->rentzestimate->amount))>0) ? $responseResult->rentzestimate->amount : 'NA';

            //Address Details
            $result_table['street'] = $response->response->results->result->address->street;
            $result_table['state'] = $response->response->results->result->address->state;
            $result_table['city'] = $response->response->results->result->address->city;
            $result_table['zipcode'] = $response->response->results->result->address->zipcode;

            $header_address = $result_table['street'].", ".$result_table['city'].", ".$result_table['state']."-".$result_table['zipcode'];

            $overall_change_img = '';
            if($result_table['overall_change']>0) {
                $overall_change_img = "<img src='http://www-scf.usc.edu/~csci571/2014Spring/hw6/up_g.gif'/>";
            }else{
                $overall_change_img = "<img src='http://www-scf.usc.edu/~csci571/2014Spring/hw6/down_r.gif'/>";
            }

            $rent_change_img = '';
            if($result_table['rent_change']>0) {
                $rent_change_img= "<img src='http://www-scf.usc.edu/~csci571/2014Spring/hw6/up_g.gif'/>";
            }else{
                $rent_change_img = "<img src='http://www-scf.usc.edu/~csci571/2014Spring/hw6/down_r.gif'/>";
            }    

            
            if( $result_table['overall_change']!='NA') $result_table['overall_change'] = abs($result_table['overall_change']);    
            if($result_table['rent_change'] != 'NA') $result_table['rent_change'] = abs($result_table['rent_change']);

            //Thousand seperator
            if($result_table['lot_size'] != 'NA') $result_table['lot_size'] = number_format((double)$result_table['lot_size'],0,'',',') . " sq.ft.";
            if($result_table['sq_ft'] != 'NA') $result_table['sq_ft'] = number_format((double)$result_table['sq_ft'],0,'',',') . " sq.ft.";    
            if($result_table['last_sold_price'] != 'NA') $result_table['last_sold_price'] = '$'.number_format((double)$result_table['last_sold_price'],2,'.',',');
            if($result_table['tax_assessment'] != 'NA') $result_table['tax_assessment'] = '$'.number_format((double)$result_table['tax_assessment'],2,'.',',');
            if($result_table['overall_change'] != 'NA') $result_table['overall_change'] = '$'.number_format((double)$result_table['overall_change'],2,'.',',');
            if($result_table['rent_change'] != 'NA') $result_table['rent_change'] = '$'.number_format((double)$result_table['rent_change'],2,'.',',');   
            if($result_table['zestimate_valuation_low'] != 'NA') $result_table['zestimate_valuation_low'] = '$'. number_format((double)$result_table['zestimate_valuation_low'],2,'.',',');
            if($result_table['zestimate_valuation_high'] != 'NA') $result_table['zestimate_valuation_high'] = '$'.number_format((double)$result_table['zestimate_valuation_high'], 2,'.',',');
            if($result_table['rentzestimate_valuation_low'] != 'NA') $result_table['rentzestimate_valuation_low'] = '$'. number_format((double)$result_table['rentzestimate_valuation_low'], 2,'.',',');         
            if($result_table['rentzestimate_valuation_high'] != 'NA') $result_table['rentzestimate_valuation_high'] = '$'.number_format((double)$result_table['rentzestimate_valuation_high'], 2,'.',',');
            if($result_table['zestimate_amount'] != 'NA') $result_table['zestimate_amount'] = '$'.number_format((double)$result_table['zestimate_amount'],2,'.',',');        
            if($result_table['rentzestimate_amount'] != 'NA') $result_table['rentzestimate_amount'] = '$'.number_format((double)$result_table['rentzestimate_amount'],2,'.',',');    

            $result_table['property_range'] = $result_table['zestimate_valuation_low'] ." - ". $result_table['zestimate_valuation_high'];
            $result_table['all_time_rent_range'] = $result_table['rentzestimate_valuation_low'] ." - ". $result_table['rentzestimate_valuation_high'];    

            //Date conversions
            if($result_table['last_sold_date']!='NA') $result_table['last_sold_date'] = date("d-M-Y",strtotime($result_table['last_sold_date']));
            if($result_table['zestimate_last_updated']!='NA') $result_table['zestimate_last_updated'] = date("d-M-Y",strtotime($result_table['zestimate_last_updated']));
            if($result_table['rentzestimate_last_updated']) $result_table['rentzestimate_last_updated'] =  date("d-M-Y",strtotime($result_table['rentzestimate_last_updated']));

            $html_text.= "<h2>Search Results</h2>";
            $html_text.= "<div style='border:1px solid;border-radius:3px;background-color:#F8F7C3;width:75%;padding:3px;text-align:left'>See more details for <a href=".$result_table['result_header']." target='_blank'>".$header_address."</a> on Zillow</div>";
            $html_text.= "<table style='width:75%'>";
            $html_text.= "<tr><td>Property Type:</td><td>".$result_table['property_type']."</td><td>Last Sold Price:</td><td align=right>".$result_table['last_sold_price']."</td></tr>";
            $html_text.= "<tr><td>Year Built:</td><td>".$result_table['year_built']."</td><td>Last Sold Date:</td><td align=right>".$result_table['last_sold_date']."</td></tr>";
            $html_text.= "<tr><td>Lot Size:</td><td>".$result_table['lot_size']."</td><td>Zestimate<sup>&reg;</sup> Property Estimate as of ".$result_table['zestimate_last_updated'].":</td><td align=right>".$result_table['zestimate_amount']."</td></tr>"; 
            $html_text.= "<tr><td>Finished Area:</td><td>".$result_table['sq_ft']."</td><td>30 Days Overall Change ".$overall_change_img.":</td><td align=right>".$result_table['overall_change']."</td></tr>"; 
            $html_text.= "<tr><td>Bathrooms:</td><td>".$result_table['bathrooms']."</td><td>All Time Property Change:</td><td align=right>".$result_table['property_range']."</td></tr>";  
            $html_text.= "<tr><td>Bedrooms:</td><td>".$result_table['bedrooms']."</td><td>Rent Zestimate<sup>&reg;</sup> Rent Valuation as of ".$result_table['rentzestimate_last_updated'].":</td><td align=right>".$result_table['rentzestimate_amount']."</td></tr>"; 
            $html_text.= "<tr><td>Tax Assessment Year:</td><td>".$result_table['tax_assessment_yr']."</td><td>30 Days Rent Change ".$rent_change_img.":</td><td align=right>".$result_table['rent_change']."</td></tr>";    
            $html_text.= "<tr><td>Tax Assessment:</td><td>".$result_table['tax_assessment']."</td><td>All Time Rent Range:</td><td align=right>".$result_table['all_time_rent_range']."</td></tr>";     
            $html_text.= "</table>";

            $html_text.= "<p>&copy; Zillow, Inc., 2006-2014. Use is subject to <a href='http://www.zillow.com/corp/Terms.htm' target='_blank'>Terms of Use</a><br><a href='http://www.zillow.com/wikipages/What-is-a-Zestimate/' target='_blank'>What is a Zestimate?</a></p>";
        }else {
            $html_text.= "<br><b>No result found</b>";
        }
    }else if($response->message->code == 508) {
        $html_text.= "<br><b>No exact match found -- Verify that the given address is correct.</b>";
    }else {
        $html_text.= "<br><b>".$response->message->text."<b>";
    }
    $street = preg_replace("/[+]/", " ", $street);
    $city = preg_replace("/[+]/", " ", $city);
}  
?> 