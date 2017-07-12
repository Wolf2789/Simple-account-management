<?php namespace php\api;
class client {
    private $username;
    private $privileges;
    private $userData = array();

    function getUsername() {
        return $this->username;
    }

    function getEmail() {
        return $this->userData['email'];
    }

    function getFirstName() {
        return $this->userData['first_name'];
    }

    function getLastName() {
        return $this->userData['last_name'];
    }

    function getPublicData() {
        return mergeAssoc(array('username' => $this->username, 'privileges' => $this->privileges), $this->getUpdateableData());
    }

    function getUpdateableData() {
        return array('email' => $this->getEmail(), 'first_name' => $this->getFirstName(), 'last_name' => $this->getLastName());
    }

    function setSessionCookie() {
//        setcookie("session", md5(microtime() . $_SERVER['REMOTE_ADDR']), time() + 60);
    }

    function sign_in($password) {
        try {
            $server = new JsonRpc("localhost/server.php");
            $result = $server->login($this->username, $password);
            if ($result > -1) {
                $this->userData = (array)$server->getUserData($this->username);
                $this->privileges = $result;
                $this->setSessionCookie();
                return true;
            }
        } catch (\Exception $e) {
            error($e->getMessage());
        }
        return false;
    }

    function sign_up($password) {
        try {
            $server = new JsonRpc("localhost/server.php");
            $result = $server->register($this->username, $password);
            if ($result > -1) {
                $this->privileges = $result;
                $this->setSessionCookie();
                return true;
            }
        } catch (\Exception $e) {
            error($e->getMessage());
        }
        return false;
    }

    function update($password, $data) {
        try {
            $server = new JsonRpc("localhost/server.php");
            if ($server->login($this->username, $password) > -1) {
                if (isset($data['newpassword']) && !empty($data['newpassword'])) {
                    $data['password'] = $data['newpassword'];
                    unset($data['newpassword']);
                }
                $data['password'] = encrypt($data['password']);

                $updateable = array('email', 'first_name', 'last_name', 'password');
                $toUpdate = array();
                foreach ($data as $k => $v)
                    if (in_array($k, $updateable))
                        $toUpdate[$k] = $v;

                if ($server->update($this->username, $password, $toUpdate)) {
                    $this->userData = (array)$server->getUserData($this->username);
                    $this->setSessionCookie();
                    return true;
                }
                return false;
            }
        } catch (\Exception $e) {
            error($e->getMessage());
        }
        return false;
    }

    function __construct($username) {
        $this->username = $username;
    }
}
