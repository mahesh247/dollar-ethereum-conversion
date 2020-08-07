<?php

/* Dollar to Ethereum or Ethereum to Dollar conversion */

$input_dollar = isset($_GET['dollars'])?$_GET['dollars']:0;
$input_ethereum = isset($_GET['ethereum'])?$_GET['ethereum']:0;
$curl = curl_init('https://markets.businessinsider.com/currency-converter/eth_united-states-dollar');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

$page = curl_exec($curl);

if(!empty($curl)) { //if any html is actually returned

    /* Find the current conversion rate */
    $dom = new DOMDocument();
    @$dom->loadHTML($page);

    // grab all the page
    $x = new DOMXPath($dom);

    $nodes = $x->query('/html/body/main/div/div/div[3]/div/div[1]/form/div[4]/div[4]/input/@value');

    foreach ($nodes as $node) {
        $name1  = $node->value;     
    }
    
    $one_ethereum_in_dollar = preg_replace('/[^0-9.]/', '', $name1);
    echo 'Current rate: 1 Ethereum is equal to $' . $one_ethereum_in_dollar;

    echo '<br />';
    echo '<br />';

    $one_dollar = 1/$one_ethereum_in_dollar;

    echo 'Conversion: <br />';
    if( isset($_GET['dollars']) && ( $input_dollar > 0 ) ) {
        $result = convert_to_etherem($input_dollar, $one_dollar);

        echo '$' . $input_dollar . ' = ' . $result . ' Ethereum';
    }
    if( isset($_GET['ethereum']) && ( $input_ethereum > 0 ) ) {
        $result = convert_to_dollars($input_ethereum, $one_ethereum_in_dollar);

        echo $input_ethereum . ' Ethereum = $'. $result;
    }
    if(!isset($_GET['dollars']) && !isset($_GET['ethereum'])) {
        echo 'Please give some input such as ?dollars=Dollar Amount or ?ethereum=Ethereum Amount';
    }   
}
else {
    /* When no conversion rate is found */
    echo 'Conversion rate error';
}

/* Convert to Ethereum */
function convert_to_etherem($dollar, $rate) {
    $result = $dollar*$rate;
    return $result;
}

/* Conver to dollars */
function convert_to_dollars($etherem, $rate) {
    $result = $etherem*$rate;
    return $result;
}