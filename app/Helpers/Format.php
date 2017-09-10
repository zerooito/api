<?php

namespace App\Helpers;

class Format
{

	public static function toChartDashboard($ordersFromDB)
	{
		$response = [];

		foreach ($ordersFromDB as $i => $order) {
			$response[$i][] = $order->data_venda;
			$response[$i][] = number_format($order->total, 2);
		}

		return $response;
	}

}