<?php
namespace app\commands;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{


    public function actionOwn()
    {
        /*$auth = Yii::$app->authManager;

        // add the rule
        $rule = new \app\rbac\AuthorRule;
        $auth->add($rule);

        $updatePost = $auth->getPermission('updatePost');
        // add the "updateOwnPost" permission and associate the rule with it.
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($updateOwnPost, $updatePost);


        $author = $auth->getRole('author');
        // allow "author" to update their own posts
        $auth->addChild($author, $updateOwnPost);*/
    }

    public function actionAdmin()
    {
        $form = new LoginForm();
        if($form->createUser('mrozk2012@gmail.com', 'q1w2e3r4')){
            echo 'User create success';
        }else{
            echo 'Error creating user';
        }
    }

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // add "createPost" permission
        $readPost = $auth->createPermission('readPost');
        $readPost->description = 'Read a post';
        $auth->add($readPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "createUsers" permission
        $createUsers = $auth->createPermission('createUsers');
        $createUsers->description = 'Create users';
        $auth->add($createUsers);

        // add "author" role and give this role the "createPost" permission
        $reader = $auth->createRole(User::ROLE_READER);
        $auth->add($reader);
        $auth->addChild($reader, $readPost);


        // add "moderator" role and give this role the "createPost" permission
        $moderator = $auth->createRole(User::ROLE_MODERATOR);
        $auth->add($moderator);
        $auth->addChild($moderator, $updatePost);
        $auth->addChild($moderator, $reader);


        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole(User::ROLE_ADMIN);
        $auth->add($admin);
        $auth->addChild($admin, $createUsers);
        $auth->addChild($admin, $moderator);
        $auth->addChild($admin, $reader);
    }
}