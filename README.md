<p>
<h1 align="center">Заготовка Yii 2 Basic + MongoDB + RBAC</h1>
</p>
<p>Заготовка для разработки базового Yii-2 приложения. Установлена БД MongoDB, на ней настроен RBAC, сделана заготовка
для админки (создание/редактирование/удаление пользователя, базовое редактирование пользователем своих данных.)
</p>
<p>
<h2>Развертывание для дальнейшей разработки</h2>
</p>
<p>
Развернуть Docker и Docker-Composer. Клонировать с гита проект в нужную папку. Затем в этой папке запустить
сборку контейнера:
<pre>
docker-composer build
</pre>
</p>
<p>
Запустить контейнер:
<pre>
docker-compose up -d
</pre>
Затем, присоединиться к этому контейнеру:
<pre>
docker exec -it Yii-Apache-MongoDb bash
</pre>
В контейнере, нужно перейти в папку с приложением:
<pre>
cd /app
</pre>
и при помощи композера инсталировать библиотеки Yii:
<pre>
composer install
</pre>
</p><p>
В данный момент база данных MongoDB абсолютно пустая, в ней отсутствуют необходимые для работы административные данные. Нужно подготовить базу
данных, внести в нее администратора и пользователя. Для этого, оставаясь в контейнере, остановим демон :
<pre>
killall mongod
</pre>
И запустим его в режиме не авторизованного доступа:
<pre>
mongod --noauth --port 27017 --dbpath /var/mongo/base --bind_ip 0.0.0.0 &
</pre>
Несколько раз нажмем "Enter" до появления приглашения консоли. Теперь, когда в базу пускают всех без разбора, подключимся к консоли БД, что бы создать админа и пользователя: 
<pre>
mongo 127.0.0.1/admin
</pre>
В консоли базы данных создадим администратора "admin" с паролем "12345678":
<pre>
use admin
db.createUser({user: "admin", pwd: "12345678", roles: [ { role: "userAdminAnyDatabase", db: "admin" } ]});
</pre>
И пользователя "test" базы данных "test" с паролем "test":
<pre>
use test
db.createUser({user: "test", pwd: "test", roles: [ { role: "dbOwner", db: "test" }]});
</pre>
Выйдем из консоли монги:
<pre>
exit
</pre>
Теперь остановим базу:
<pre>
killall mongod
</pre>
И запустим в нормальном режиме
<pre>
mongod --auth --port 27017 --dbpath /var/mongo/base --bind_ip 0.0.0.0 &
</pre>
Несколько раз нажмем "Enter" до появления приглашения консоли. Теперь, когда БД готова к общению, выполним миграции,
которые создадут необходимые коллекции с правами, ролями, и двумя пользователями:
<pre>
/app/yii mongodb-migrate/up
</pre>
Теперь можно зайти на наш сервер бараузером, и залогиниться как administrator/administrator или user/1234567890.
Дополнительные пункты меню:<ul>
<li>"User" - редактирование пользователем своих параметров.</li>
<li>"Users" - Админский редактор пользователей и назначение им ролей.</li>
</ul>
Редактирование правил и создание новых ролей подразумевается миграциями.
</p>
<p>
<h2>Перенос на продакшен</h2>
</p>
<p>Для выгрузки на продакшен, копируется каталог html, содержащий Yii2, либо клонируем проект и создаем созадем симлинк на папку web.
После копирования, в подкаталоге config, дублируем файл <code>mongoDb.php</code> в <code>prodMongoDb.php</code>. В скопированном файле изменяем настройки для подключения к mongoDb на продакшене.
</p>
<p>
Перейдем на сервере в корень проекта (куда поместили каталог html) и применим миграции:
<pre>
./yii mongodb-migrate/up
</pre>
</p>
<p>Если необходимо использовать MySQL, копируем еще файл <code>db.php</code> в файл <code>prodDb.php</code>, где и задаем настройки подключения к серверу MySQL продакшена.</p>
<p>Таким образом, настройки продакшена остануться только на продакшене, и не будут мешатьсся при обновлении или клонировании проекта.</p>
