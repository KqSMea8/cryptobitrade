<?php
use App\Currency;

/**
 * Returns default string 'active' if route is active.
 *
 * @param $route
 * @param string $str
 * @return string
 */
function set_active($route, $str = 'active') {
  
  return (Request::is($route) && !Request::is($route.'/*')) ? $str : '';

}

function currencyConvert($amount,$symbol_placement='left',$currency = 'USD') {
	$currency = currency()->getUserCurrency();
	$symbol = Currency::where('code',$currency)->value('symbol');
	$converted = currency($amount);	
	$markup = $symbol.' '.$converted;
	if($symbol_placement === 'right'){
		$markup = $converted.' '.$symbol;
	}
	return $markup;
}
