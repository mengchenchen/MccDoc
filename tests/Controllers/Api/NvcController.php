<?php

namespace Mengcc\Tests\Controllers\Api;

class NvcController
{
	/**
	 * @MccDoc({
	 * "name":"获取订单列表",
	 * "url":"api/nvc/handle",
	 * "method":"GET",
	 * "description":"获取订单列表"
	 * })@
	 * @author mengchenchen
	 */
	public function handle()
	{
	}

	/**
	 * @MccDoc({
	 * "name":"工人派单",
	 * "url":"api/nvc/worker_dispatch",
	 * "method":"POST",
	 * "description":"工人派单",
	 * "params":{
	 * "orderNo":"工单号",
	 * "worker_name":"工人名称",
	 * "worker_mobile":"工人手机号"
	 * }
	 * })@
	 */
	public function worker_dispatch()
	{
	}

	/*
	 * @MccDoc({
	 * "name":"预约",
	 * "url":"api/nvc/make_an_appointment",
	 * "method":"POST",
	 * "description":"预约",
	 * "params":{
	 * "orderNo":"工单号",
	 * "name":"预约人姓名",
	 * "time":"预约时间",
	 * "remark":"备注"
	 * }
	 * })@
	 * @author mengchenchen
	 */
	public function make_an_appointment()
	{

	}

	/**
	 * @MccDoc({
	 * "name":"雷士退单",
	 * "url":"api/nvc/refund",
	 * "method":"POST",
	 * "description":"雷士退单",
	 * "params":{
	 * "orderNo":"工单号",
	 * "name":"预约人姓名",
	 * "time":"预约时间",
	 * "reason":"退单原因"
	 * }
	 * })@
	 * @author mengchenchen
	 */
	public function refund()
	{
	}

	/**
	 * @MccDoc({
	 * "name":"雷士订单完工",
	 * "url":"api/nvc/order_complete",
	 * "method":"POST",
	 * "description":"雷士订单完工",
	 * "params":{
	 * "orderNo":"工单号",
	 * "items":"完工项"
	 * }
	 * })@
	 * @author mengchenchen
	 */
	public function order_complete()
	{
	}

	/**
	 * @MccDoc({
	 * "name":"修改工单备注",
	 * "url":"api/nvc/edit_order_remark",
	 * "method":"POST",
	 * "description":"修改工单备注",
	 * "params":{
	 * "orderNo":"工单号",
	 * "dispatchStat":"备注主题(例：已接单、已派单、转派师傅)6个汉字以内",
	 * "remark":"备注记录内容(例：转派师傅:xxx,联系电话:xxxxx)"
	 * }
	 * })@
	 * @author mengchenchen
	 */
	public function edit_order_remark()
	{
	}
}
