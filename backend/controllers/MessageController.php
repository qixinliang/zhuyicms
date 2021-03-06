<?php

namespace backend\controllers;

use yii\web\Controller;
use common\models\Message;
use yii;

class MessageController extends controller {
	public function actionIndex(){
		//最新通知取最近10条数据
		$num = 10;
		$messageM = new \common\models\Message();
		$ret = $messageM->getLatestMessage($num);
		$data1 = array();
		if(!empty($ret)){
			foreach($ret as $r){
				$dataTmp = array(
					'time' => isset($r->update_time)? $r->update_time : time(),
					'contents' => isset($r->contents)? $r->contents : '',
				);
				$data1[] = $dataTmp;
			}
		}
		$data1 = array(
        	array(
            	'time' => 100,
                'contents' => '用户A发起了新的需求'
           ),
           array(
                'time' => 1000,
              	'contents' => '用户B发起了新的需求'
           ),

       	);
		
	
		//未处理需求。
		$ret = $messageM->getUnHandledProject();
		$data2 = array();
		if(!empty($ret)){
			foreach($ret as $r){
				$dataTmp = array(
					'time' => isset($r->update_time)? $r->update_time : time(),
					'contents' => isset($r->contents)? $r->contents : '',
					'project_id' => isset($r->project_id)? $r->project_id : -1 
				);
				$data2[] = $dataTmp;
			}
		}
		$data2 = array(
			array(
				'time' => 100,
				'contents' => '用户A发起了新的需求',
				'project_id' => 6
			),
			array(
				'time' => 1000,
				'contents' => '用户B发起了新的需求',
				'project_id' => 10
			),

		);

		//未处理订单。
		$ret = $messageM->getUnhandledOrder();
		$data3 = array();
		if(!empty($ret)){
			foreach($ret as $r){
				$dataTmp = array(
					'time' => isset($r->update_time)? $r->update_time : time(),
					'contents' => isset($r->contents)? $r->contents : '',
					'order_id' => isset($r->order_id)? $r->order_id : -1
				);
				$data3[] = $dataTmp;
			}
		}
		$data3 = array(
			array(
				'time' => 100,
				'contents' => '用户A发起了新的订单',
				'order_id' => 6
			),
			array(
				'time' => 1000,
				'contents' => '用户B发起了新的订单',
				'order_id' => 10
			),
		);
		return $this->render('index',['data1' => $data1,'data2' => $data2,'data3' => $data3]);
	}

    //客服代办的消息列表展示
    public function actionHome(){

		$message = Message::find();

		$pagination = new \yii\data\Pagination(['totalCount' => $message->count() , 'pageSize' => 10]);

		$data = $message->offset($pagination->offset)->limit($pagination->limit)->where(['status'=>0])->all();

		return $this->render('home' , ['data' => $data , 'pagination' => $pagination]);
	}

    //管理消息列表
    public function actionTest(){

		$message = Message::find();

		$pagination = new \yii\data\Pagination(['totalCount' => $message->count() , 'pageSize' => 10]);

		$data = $message->offset($pagination->offset)->limit($pagination->limit)->all();

		return $this->render('index' , ['data' => $data , 'pagination' => $pagination]);
	}

    //添加一条记录
	public function actionAdd(){

		$model = new Message();

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()){

			return $this->redirect(['index']);
		}
		return $this->render('add' , ['model' => $model]);
	}

    //根据主健id修改一条记录
	public function actionEdit($id){
		$id = (int)$id;
		if($id > 0 && ($model = Message::findOne($id))){

			if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save() ){
				return $this->redirect(['index']);
			}
			return $this->render('edit' , ['model' => $model]);
		}

		return $this->redirect(['index']);
	}

	/*
    //根据主健id删除一条记录
	public function actionDelete($id){
		$id = (int)$id;
		if($id > 0){
			Message::findOne($id)->delete();
		}
		return $this->redirect(['index']);

	}*/

	public function actionDelete($id,$type){
		if($type == 0){//需要根据project_id删除
			$projectId = $id;
			Message::deleteAll(['project_id' => $projectId]);
		}else if($type == 1){ //根据order_id删除
			$orderId = $id;
			Message::deleteAll(['order_id' => $orderId]);
		}
		return $this->redirect(['index']);
	}

    /**
	 * 全局函数 ，添加一条代办消息
	 * @param string $contents 代办内容
	 * @param string $link 跳转的连接 m/c
     * @param int $type 消息类型 0 是全局 ，1是用户，2 是后台 （ps: 根据实际情况定义）
     * @param int $status 消息状态 0 是未读 ，1 是已读
	 */
    public function _Intodo($contents,$link='',$type=1,$status=0){
        if(!$contents) return;
        $connection = \Yii::$app->db;
        // INSERT
        $connection->createCommand()->insert('zyj_message', [
            'contents' => $contents,
            'link' => $link,
            'type' => $type,
            'status' => $status,
            'create_time' => time(),
            'update_time' => 0,

        ])->execute();
	}
}
