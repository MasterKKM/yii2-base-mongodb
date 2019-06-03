<?php
/**
 * Хелпер для отображения статуса пользователя.
 */

namespace app\modules\admin\helpers;


class StatusHelper
{
    private static $allStatus = [0 => 'не активен', 1 => 'активен'];

    /**
     * Текстовое представление статуса
     * @param $model \app\models\User
     * @return string
     */
    public static function statusFullText($model)
    {
        if (isset(static::$allStatus[$model->status])) {
            return static::$allStatus[$model->status];
        }
        return '-=Error=-';
    }

    /**
     * Получить список статусов.
     * @return array
     */
    public static function getAllStatus()
    {
        return static::$allStatus;
    }
}