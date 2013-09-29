<?php
    class Router
    {
        public static $instance = null;
        protected $scriptPath;
        public $params;
    
        private function __construct()
        {

            $this->params = array();
            if (!empty($_SERVER['PATH_INFO']))
            {
                $this->params = explode('/', trim(trim($_SERVER['PATH_INFO'], '/')));
            }
            $this->scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        }
        
        public function __destruct()
        {}
        
        private function __clone()
        {}
        
        public static function &instance()
        {
            if (self::$instance === null)
            {
                self::$instance = new Router();
            }
            return self::$instance;
        }
        
        public function getPagePath()
        {
            $page = 'default';
            if (isset($_SESSION['page']))
            {
                $page = $_SESSION['page'];
            }

            $path = iQUIZ_ROOT . DS . 'pages' . DS . $page . '.php';

            if (file_exists($path))
            {
                return $path;
            }
            else
            {
                $this->redirectToPage('default');
            }
        }

        public function redirect($url)
        {
            header('Location: ' . $url);
            ob_end_clean();
            die();
        }

        public function redirectToPage($page, array $params = array())
        {
            $_SESSION['page'] = $page;
            $url = $this->scriptPath . '/index.php';
            if (count($params) > 0)
            {
                $url .= '/' . implode('/', $params);
            }
            $this->redirect($url);
        }

        public function reload()
        {
            header('Location: ' . $_SERVER['REQUEST_URI']);
            ob_end_clean();
            die();
        }
        
        public function countParams()
        {
            return count($this->params);
        }

        public function getParam($index)
        {
            if (isset($this->params[$index]))
            {
                return $this->params[$index];
            }
            else
            {
                return null;
            }
        }
    }
?>
