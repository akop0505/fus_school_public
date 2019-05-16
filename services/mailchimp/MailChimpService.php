<?php

namespace app\services\mailchimp;

use app\models\User;
use Yii;

class MailChimpService
{
    public function __construct()
    {
        $this->url = Yii::$app->params["MailChimpApiPath"];
        $this->key = Yii::$app->params["MailChimpApiKey"];
        $this->listId = Yii::$app->params["MailChimpListId"];
    }

    /**
     * @param User $model
     * @return mixed
     */
	public function subscribeUser(User $model)
    {
        $institutionName = "";
        if($model->institution){
            $institutionName = $model->institution->name ?: "";
        }
        $data = (object)[
            "members"=>(array)[
                (object)[
                    "email_address"=>$model->email,
                    "status" => "subscribed",
                    "merge_fields"=>(object)[
                        "FNAME"=>$model->firstName,
                        "LNAME"=>$model->lastName,
                        "SCHOOL"=>$institutionName
                    ]
                ]
            ],
            "update_existing"=>true
        ];

        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: apikey '.$this->key
        ));
        curl_setopt($ch,CURLOPT_URL, $this->url."/lists/".$this->listId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function memberStatus($emial) {
	    $result = self::getMember($emial);
	    if(isset($result->status)){
            switch ($result->status) {
                case "subscribed":
                    return 1;
                case "unsubscribed":
                case "cleaned":
                case "pending":
                    return 0;
                default:
                    return 0;
            }
        }
    }

    public function getMember($email) {
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: apikey '.$this->key
        ));
        curl_setopt($ch,CURLOPT_URL, $this->url."/lists/".$this->listId."/members/".md5($email));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return json_decode($result);
    }

    public function removeMember($email) {
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: apikey '.$this->key
        ));
        curl_setopt($ch,CURLOPT_URL, $this->url."/lists/".$this->listId."/members/".md5($email));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return json_decode($result);
    }
}