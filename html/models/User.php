<?php

namespace app\models;

use HttpException;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * Class User
 * @package app\models
 * @property object _id
 * @property string username логин
 * @property string password пароль
 * @property string authKey
 * @property string accessToken
 * @property boolean status активность пользователя (включен/выключен)
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function fields()
    {
        return [
            '_id',
            'username',
            'password',
            'authKey',
            'accessToken',
            'status',
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'authKey',
            'accessToken' => 'Токен доступа',
            'status' => 'Статус',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->fields();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['status'], 'filter', 'filter' => function ($value) {
                if (is_numeric($value)) {
                    $value = !($value == 0);
                }
                return $value;
            }],
            ['status', 'default', 'value' => true],
            [['authKey', 'accessToken'], 'default', 'value' => ''],
            [['username', 'password'], 'trim'],
            [['username', 'status'], 'required'],
            ['username', 'unique'],
            ['password', 'required', 'on' => ['create']],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        $oldPassword = trim($this->getOldAttribute('password'));
        $this->password = trim($this->password);

        var_dump($this->password);
        if ($this->isNewRecord || $this->password != '' && ($this->getAttribute('password') != $oldPassword)) {
            // Для новой записи или при смене пароля, сохраняем вмето пароля его хеш.
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        } else {
            // Если пароль пустой, восстанавливаем предыдущий.
            $this->password = $oldPassword;
        }

        var_dump($this->password);
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id, 'status' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (trim($token) == '') {
            return null;
        }
        return static::findOne(['accessToken' => $token, 'status' => true]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        if (trim($this->authKey) == '') {
            return null;
        }
        return $this->authKey === $authKey;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Добавить роли текущему пользователю.
     * @param $roles
     * @throws \Exception
     */
    public function addRoles($roles)
    {
        if (is_array($roles)) {
            $auth = Yii::$app->authManager;
            /* @var $auth \yii\mongodb\rbac\MongoDbManager */
            $curRoles = $auth->getRoles();
            foreach ($roles as $role) {
                if (isset($curRoles[$role])) {
                    $auth->assign($curRoles[$role], $this->_id);
                } else {
                    throw new HttpException(500, 'Попытка присвоения пользователю не существующей роли.');
                }
            }
        }
    }

    /**
     * Получить роли текущего пользователя.
     * @return array
     */
    public function getRoles()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRolesByUser($this->_id), 'name', 'description');
    }

    /**
     * Очистить список ролей пользователя.
     */
    public function clearRoles()
    {
        Yii::$app->authManager->revokeAll($this->_id);
    }

    /**
     * Заменить список ролей.
     * @param $roles array
     * @throws \Exception
     */
    public function updateRoles($roles)
    {
        $this->clearRoles();
        $this->addRoles($roles);
    }
}
