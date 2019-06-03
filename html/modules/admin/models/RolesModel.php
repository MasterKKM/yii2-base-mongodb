<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Модель для выбора ролей пользователя.
 * Class RolesModel
 * @package app\modules\admin\models
 */
class RolesModel extends Model
{
    private $values = [];
    private $description;


    /**
     * RolesModel constructor.
     * @param $user \app\models\User
     * @param array $config
     */
    public function __construct($user, array $config = [])
    {
        parent::__construct($config);

        $allRoles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
        $this->description = $allRoles;
        $this->values = array_fill_keys(array_keys($allRoles), false);
        $currentRoles = array_keys($user->getRoles());
        foreach ($currentRoles as $roleName) {
            $this->values[$roleName] = true;
        }

    }

    /**
     * Генерируем правило валидации - всё сохранять.
     * @return array
     */
    public function rules()
    {
        return [
            [array_keys($this->values), 'safe']
        ];
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \yii\base\UnknownPropertyException
     */
    public function __set($name, $value)
    {
        if (isset($this->values[$name])) {
            $this->values[$name] = !empty($value);
            return;
        }
        parent::__set($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if (isset($this->values[$name])) {
            return true;
        }
        return parent::__isset($name);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->description;
    }

    /**
     * Получаем список выбранных ролей.
     * @return array
     */
    public function onlySelected()
    {
        return array_keys($this->values, true);
    }
}