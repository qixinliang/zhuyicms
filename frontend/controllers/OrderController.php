<?php
namespace frontend\controllers;

use yii;
use yii\web\Controller;
use frontend\models;
use frontend\services;

class OrderController extends Controller{
	public function actionIndex(){
		//if(($request = Yii::$app->request->post('data'))){
		if(1){
			//上一个接口传过来的数据
			$dataFromZP = json_encode(
				array(
					'user_id'=>'1',
					'project_id'=>'3',
					'designer_ids'=> array(5,6,7),
				)
			);
		
			$dataRetrived = json_decode($dataFromZP);	
			$designerIds = $dataRetrived->designer_ids;

			//创建三个订单，并且设置状态为待设计师确认。
			$finalData = array();
			foreach($designerIds as $d){
				$data = array(
					'user_id' 				=> $dataRetrived->user_id, 
					'project_id' 			=> $dataRetrived->project_id,
					'designer_id' 			=> $d,
					'status'				=> \frontend\models\Order::STATUS_WAITING_DESIGNER_TO_CONFIRM,
					'appointment_time' 		=> time(),
					'appointment_location' 	=> 'bj',
					'remark' 				=> '新订单',
					'service_type' 			=> '1',
					'create_time' 			=> time(),
					'update_time' 			=> time(),
				);
				$orderModel = new \frontend\models\Order();
				$orderModel->addOrder($data);
				$finalData[] = $data;
			}
			return $this->render("index",['final_data' => $finalData]);
		}

		$userId = 1;
		//根据当前的userId去获取我的订单。
		$orders = \frontend\models\Order::getOrdersByUserId($userId);
		return $this->render("index",['final_data' => $finalData]);
	}

	public function actionSetStatus($orderId,$status){
		$orderModel = new \frontend\models\Order();
		$orderModel->setStatus($orderId,$status);
	}
	
	public function actionTest(){
		echo ("test action!!!");
		$orderModule = new \frontend\services\Order(1);
		$orderModule->test();
	}
}
