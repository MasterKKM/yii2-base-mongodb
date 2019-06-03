<?php
/**
 * Начальная инициализация RBAC.
 * Создание администратора.
 */

use app\models\User;

class m190520_095239_rback_initial extends \yii\mongodb\Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\Exception
     * @throws \Exception if data validation or saving fails (such as the name of the role or permission is not unique)
     */
    public function up()
    {
        $auth = Yii::$app->authManager;

        // Удалить все правила.
        $auth->removeAllRules();

        // Право использовать интерфейс администратора.
        $adminInterface = $auth->createPermission('adminInterface');
        $adminInterface->description = 'Интерфейс администратора';
        $auth->add($adminInterface);

        // Право использовать интерфейс пользователя.
        $userInterface = $auth->createPermission('userInterface');
        $userInterface->description = 'Интерфейс пользователя';
        $auth->add($userInterface);

        // Роль пользователя.
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);
        $auth->addChild($user, $userInterface);

        // Роль администратора.
        // Наследует от роли пользователя.
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        $auth->addChild($admin, $adminInterface);
        $auth->addChild($admin, $user);

        // Создание пользователя 'administrator'.
        $model = new User();
        $model->username = 'administrator';
        $model->password = 'administrator';
        $model->status = true;
        if (!$model->save()) {
            var_dump($model->getErrors());
            die;
        }
        $model->addRoles(['admin']);

        // Создание пользователя 'user'.
        $model = new User();
        $model->username = 'user';
        $model->password = '1234567890';
        $model->status = true;
        if (!$model->save()) {
            var_dump($model->getErrors());
            die;
        }
        $model->addRoles(['user']);

    }

    /**
     * @return bool
     */
    public function down()
    {
        // Удалить все правила, и пользователей.
        Yii::$app->authManager->removeAll();
        $this->remove(User::collectionName(), ['username' => 'administrator']);
        $this->remove(User::collectionName(), ['username' => 'user']);

        return true;
    }
}
