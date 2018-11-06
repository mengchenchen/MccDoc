<?php

namespace Mengcc\Tests\Controllers\Api;

class TestController
{
	/**
	 * @MccDoc({
	 * "name":"首页",
	 * "group":"用户中心",
	 * "url":"/api/index",
	 * "method":"GET",
	 * "description":"首页"
	 * })@
	 * @author mengchenchen
	 */
	public function index()
	{
	}

	/**
	 * @MccDoc({
	 * "name":"编辑页面",
	 * "url":"/api/edit",
	 * "group":"用户中心",
	 * "method":"POST",
	 * "description":"编辑页面",
	 * "params":{
	 *        "order_id":"工单号",
	 *        "worker_name":"工人名称",
	 *        "worker_mobile":"工人手机号"
	 * }
	 * })@
	 */
	public function edit()
	{
	}

}
